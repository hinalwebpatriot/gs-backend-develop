<?php

namespace lenal\catalog\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use lenal\catalog\Enums\DiamondTypeEnum;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Culet;
use lenal\catalog\Models\Diamonds\Cut;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Fluorescence;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\catalog\Models\Diamonds\Polish;
use lenal\catalog\Models\Diamonds\Symmetry;
use lenal\catalog\Models\FilterDescription;
use lenal\catalog\Models\ProductCategory;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Resources\DiamondResource;
use lenal\catalog\Resources\DiamondsCollection;
use lenal\catalog\Resources\ProductCategoryResource;
use lenal\catalog\Resources\FilterDescriptionCollection;
use lenal\catalog\Resources\ShapeBannersCollection;
use lenal\MarginCalculate\Facades\MarginCalculate;

/**
 * Class DiamondsHelper
 *
 * @package lenal\catalog\Helpers
 */
class DiamondsHelper
{
    use ModelRelationValue;

    protected static $ranged_related_options = [
        'cut'          => [
            'field' => 'cut_id',
            'model' => Cut::class,
        ],
        'color'        => [
            'field' => 'color_id',
            'model' => Color::class,
        ],
        'clarity'      => [
            'field' => 'clarity_id',
            'model' => Clarity::class,
        ],
        'fluorescence' => [
            'field' => 'fluorescence_id',
            'model' => Fluorescence::class,
        ],
        'polish'       => [
            'field' => 'polish_id',
            'model' => Polish::class,
        ],
        'symmetry'     => [
            'field' => 'symmetry_id',
            'model' => Symmetry::class,
        ],
        'manufacturer' => [
            'field' => 'manufacturer_id',
            'model' => Manufacturer::class,
        ]
    ];

    protected static $ranged_inline_options = [
        'depth',
        'table',
        'size_ratio',
        'girdle',
        'certificate',
        'video',
    ];

    protected static $other_options = [
        'shape' => [
            'field' => 'shape_id',
            'model' => Shape::class,
        ]
    ];

    protected static $sort_fields = [
        'color',
        'clarity',
        'cut',
        'carat',
        'calculated_price',
    ];

    /**
     * @return DiamondsCollection
     */
    public function getDiamondsFeed(): DiamondsCollection
    {
        $filter_array = request()->except(['page']);
        $diamonds_query = $this->filterDiamonds($filter_array);

        return $this->returnSortedCollection($diamonds_query);
    }

    /**
     * @param EngagementRing $ring
     * @return DiamondsCollection
     */
    public function getDiamondsForConstructor($ring): DiamondsCollection
    {
        $filter_array = request()->except(['page', 'shape']);
        $diamonds_query = $this->filterDiamonds($filter_array);
        $diamonds_query = $diamonds_query
            ->where('enabled', 1)
            ->where('shape_id', $ring->stoneShape->id);
        if (isset($filter_array['carat'])) {
            $carat = $filter_array['carat'];
            $diamonds_query = $diamonds_query
                ->whereBetween('carat', [
                    $ring->min_stone_carat > (float)$carat['min'] ? $ring->min_stone_carat : (float)$carat['min'],
                    $ring->max_stone_carat < (float)$carat['max'] ? $ring->max_stone_carat : (float)$carat['max']
                ]);
        } else {
            $diamonds_query = $diamonds_query
                ->whereBetween('carat', [$ring->min_stone_carat, $ring->max_stone_carat]);
        }
        return $this->returnSortedCollection($diamonds_query);
    }

    private function returnSortedCollection($diamonds_query): DiamondsCollection
    {
        $per_page = request()->input('per_page') ?? 20;
        $sort = request()->input('sort');

        //TODO поменять фильтр под реальную стоимость товара
        if ($sort['field'] === 'price') {
            $sort['field'] = 'calculated_price';
        }

        if (in_array($sort['field'], self::$sort_fields)) {
            return new DiamondsCollection($diamonds_query->orderBy($sort['field'],
                $sort['order'])->paginate($per_page));
        }

        return new DiamondsCollection($diamonds_query->paginate($per_page));
    }

    public function filterDiamonds($filter_array)
    {
        $notActive = Manufacturer::query()->where('is_active', 0)->toBase()->pluck('id');

        /** @var Diamond|Builder $diamonds_query */
        $diamonds_query = Diamond::withCalculatedPrice()
            ->withResourceRelation()
            ->where('enabled', 1)
            ->where('type', Arr::get($filter_array, 'type', DiamondTypeEnum::NATURAL()))
            ->where('is_offline', (int) Arr::get($filter_array, 'offline', 0))
            ->whereNotIn('manufacturer_id', $notActive);

        if (!empty($filter_array["video"]) && $filter_array["video"] == true) {
            $diamonds_query = $diamonds_query->where([['video', '<>', null], ['video', '<>', '-']]);
            unset($filter_array["video"]);
        }
        //TODO поменять фильтр под реальную стоимость товара
        if (array_key_exists('price', $filter_array)) {
            $diamonds_query->searchByCalculatedPrice((float) $filter_array['price']['min'],
                (float) $filter_array['price']['max']);
        }

        foreach ($filter_array as $option_key => $option_value) {
            if ($option_key == 'carat') {
                $diamonds_query = $diamonds_query->whereBetween($option_key, [(float)$option_value['min'], (float)$option_value['max']]);
            }
            if (isset(self::$ranged_related_options[$option_key])) {
                $data = self::$ranged_related_options[$option_key]['model']::query()
                    ->whereBetween('value', [(int) $option_value['min'], (int) $option_value['max']])
                    ->toBase()
                    ->pluck('id');
                $diamonds_query->whereIn(self::$ranged_related_options[$option_key]['field'], $data);
            }
            if (in_array($option_key, self::$ranged_inline_options)) {
                $diamonds_query = $diamonds_query->whereBetween($option_key, [(float)$option_value['min'], (float)$option_value['max']]);
            }
            if (isset(self::$other_options[$option_key])) {
                $data = self::$other_options[$option_key]['model']::query()
                    ->whereIn('slug', is_array($option_value) ? $option_value : [$option_value])
                    ->toBase()
                    ->pluck('id');
                $diamonds_query->whereIn(self::$other_options[$option_key]['field'], $data);
            }
        }
        /* $diamonds_query
            ->with(array_merge(
                array_keys(self::$ranged_related_options),
                ['media'],
                array_keys(self::$other_options)
            )); */
        return $diamonds_query;
    }

    public function getFilters()
    {
        $filters = [];
        $filters['carat']['min'] = Diamond::min('carat');
        $filters['carat']['max'] = Diamond::max('carat');
        $filters['price']['min'] = Diamond::withMinCalculatedPrice()->first()->min_calculated_price;
        $filters['price']['max'] = Diamond::withMaxCalculatedPrice()->first()->max_calculated_price;
        $filters['depth']['min'] = Diamond::where('depth', '>', 0)->min('depth');
        $filters['depth']['max'] = Diamond::max('depth');
        $filters['table']['min'] = Diamond::where('table', '>', 0)->min('table');
        $filters['table']['max'] = Diamond::max('table');
        $filters['size_ratio']['min'] = Diamond::min('size_ratio');
        $filters['size_ratio']['max'] = Diamond::max('size_ratio');

        $filtersResponse = $filters;
        return response($filtersResponse, 200);
    }

    public function shapesBanner()
    {
        return new ShapeBannersCollection(Shape::all());
    }

    public function productCategories()
    {
        return ProductCategoryResource::collection(ProductCategory::all());
    }

    public function productCategoriesSuggested()
    {
        return ProductCategoryResource::collection(ProductCategory::whereIsSuggested(true)->get());
    }

    /**
     * @param  Diamond  $diamond
     *
     * @return float|null
     */
    public function getDiamondMargin(Diamond $diamond): ?float
    {
        $margin_data = MarginCalculate::findMargin([
            'manufacturer_id' => $diamond->isModel($diamond->manufacturer) ? $diamond->manufacturer->id : null,
            'clarity_id'      => $diamond->isModel($diamond->clarity) ? $diamond->clarity->id : null,
            'color_id'        => $diamond->isModel($diamond->color) ? $diamond->color->id : null,
            'is_round'        => $diamond->isModel($diamond->shape) ? $diamond->shape->slug == 'round' : false,
            'carat'           => $diamond->carat,
        ]);

        return $margin_data->margin ?? 0;
    }

    /**
     * @param  Request  $request
     *
     * @return Diamond
     */
    public function createDiamond(Request $request): Diamond
    {
        $diamond = Diamond::create([
            'enabled'            => (int) $request->get('enabled', 1),
            'sku'                => $request->get('sku'),
            'slug'               => $request->get('slug'),
            'carat'              => (float) $request->get('carat'),
            'depth'              => (float) $request->get('depth'),
            'table'              => (float) $request->get('table'),
            'length'             => $request->get('length'),
            'width'              => $request->get('width'),
            'height'             => $request->get('height'),
            'size_ratio'         => $request->get('size_ratio'),
            'raw_price'          => $request->get('raw_price'),
            'stock_number'       => $request->get('stock_number'),
            'girdle'             => $request->get('girdle'),
            'certificate'        => $request->get('certificate'),
            'certificate_number' => $request->get('certificate_number'),
            'video'              => $request->get('video'),
            'manufacturer_id'    => $this->getRelationValue(
                Manufacturer::class,
                $request->get('manufacturer')
            ),
            'shape_id'           => $this->getRelationValue(
                Shape::class,
                $request->get('shape')
            ),
            'color_id'           => $this->getRelationValue(
                Color::class,
                $request->get('color')
            ),
            'clarity_id'         => $this->getRelationValue(
                Clarity::class,
                $request->get('clarity')
            ),
            'cut_id'             => $request->has('cut')
                ? $this->getRelationValue(
                    Cut::class,
                    $request->get('cut')
                )
                : null,
            'polish_id'          => $request->has('polish')
                ? $this->getRelationValue(
                    Polish::class,
                    $request->get('polish')
                )
                : null,
            'symmetry_id'        => $request->has('symmetry')
                ? $this->getRelationValue(
                    Symmetry::class,
                    $request->get('symmetry')
                )
                : null,
            'fluorescence_id'    => $request->has('fluorescence')
                ? $this->getRelationValue(
                    Fluorescence::class,
                    $request->get('fluorescence')
                )
                : null,
            'culet_id'           => $request->has('culet')
                ? $this->getRelationValue(
                    Culet::class,
                    $request->get('culet')
                )
                : null,
        ]);

        $preview_image = $request->file('image');
        if ($preview_image instanceof UploadedFile) {
            $diamond
                ->addMedia($preview_image)
                ->withResponsiveImages()
                ->toMediaCollection('diamond-images');
        }

        return $diamond;
    }

    /**
     * @param  string  $sku
     * @return boolean
     */
    public function deleteDiamond(string $sku)
    {
        $diamond = Diamond::where('sku', $sku)
            ->firstOrFail();
        $rez = $diamond->delete();
        return $rez;
    }

    /**
     * @param  string   $sku
     * @param  Request  $request
     *
     * @return Diamond
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateDiamond(string $sku, Request $request): Diamond
    {
        /** @var Diamond $diamond */
        $diamond = Diamond::withCalculatedPrice()
            ->where('sku', $sku)
            ->firstOrFail();

        collect([
            'enabled',
        ])->each(function ($model_property) use ($diamond, $request) {
            if ($request->has($model_property)) {
                $diamond->{$model_property} = (int) $request->get($model_property);
            }
        });

        collect([
            'slug',
            'carat',
            'video',
            'depth',
            'table',
            'length',
            'width',
            'height',
            'girdle',
            'certificate',
            'size_ratio',
            'raw_price',
            'stock_number',
            'certificate_number',
            'certificate',
        ])->each(function ($model_property) use ($diamond, $request) {
            if ($request->has($model_property)) {
                $diamond->{$model_property} = $request->get($model_property);
            }
        });

        collect([
            'manufacturer' => Manufacturer::class,
            'shape'        => Shape::class,
            'clarity'      => Clarity::class,
            'color'        => Color::class,
            'cut'          => Cut::class,
            'symmetry'     => Symmetry::class,
            'polish'       => Polish::class,
            'fluorescence' => Fluorescence::class,
            'culet'        => Culet::class,
        ])->each(function ($relation_class, $relation_name) use ($diamond, $request) {
            if ($request->has($relation_name)) {
                $this->associateRelation(
                    $diamond->{$relation_name}(),
                    $relation_class,
                    $request->get($relation_name)
                );
            }
        });

        $diamond->save();

        $preview_image = $request->file('image');
        if ($preview_image instanceof UploadedFile) {
            $diamond->clearMediaCollection('diamond-images');
            $diamond
                ->addMedia($preview_image)
                ->withResponsiveImages()
                ->toMediaCollection('diamond-images');
        }

        return $diamond;
    }

    /**
     * @param  Relation     $relation
     * @param  string       $relationClass
     * @param  null|string  $slug
     *
     * @return null
     */
    private function associateRelation(Relation $relation, string $relationClass, ?string $slug)
    {
        if (!class_exists($relationClass)) {
            return null;
        }

        $relation->associate(
            !is_null($slug)
                ? $relationClass::where('slug', $slug)->first()
                : null
        );
    }


    public function similarItems($id)
    {
        $diamond = Diamond::where('id', $id)->first();
        if (!$diamond || !$diamond->carat || !$diamond->shape || !$diamond->raw_price) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        $similar = Diamond
            ::withCalculatedPrice()
            ->withResourceRelation()
            ->where([
                'shape_id' => $diamond->shape_id,
            ])
            ->where('id', '!=', $diamond->id)
            ->whereBetween('raw_price', [$diamond->raw_price * 0.8, $diamond->raw_price * 1.2])
            ->whereBetween('carat', [$diamond->carat - 0.1, $diamond->carat + 0.1])
            ->take(10)
            ->get();
        return DiamondResource::collection($similar);
    }

    public function recommendDiamonds($shape_id, $carat_range): DiamondsCollection
    {
        $filter_array = request()->except(['page', 'carat', 'shape']);
        $diamonds_query = $this->filterDiamonds($filter_array);

        $diamonds_query = $diamonds_query
            ->where('enabled', 1)
            ->where('shape_id', $shape_id);

        if ($carat_range) {
            $diamonds_query->whereBetween('carat', $carat_range);
        }

        $diamonds_query->inRandomOrder()->take(10);

        return $this->returnSortedCollection($diamonds_query);
    }

    public function getManufacturerFromSku($sku)
    {
        $prefix = strchr($sku, '-', true);
        return array_search($prefix, config('import.stock_number_prefix'));
    }
}
