<?php

namespace lenal\catalog\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Helpers\FilterTypes\FilterHavingWithRange;
use lenal\catalog\Helpers\FilterTypes\FilterWithProperty;
use lenal\catalog\Helpers\FilterTypes\FilterWithRelationSlug;
use lenal\catalog\Helpers\FilterTypes\FilterWithRelationSlugCondition;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Resources\EngagementRingGroupCollection;
use lenal\catalog\Resources\MetalResource;
use lenal\catalog\Resources\RingCollectionResource;
use lenal\catalog\Resources\RingSizeResource;
use lenal\catalog\Resources\RingStyleResource;
use lenal\offers\Models\Offer;
use lenal\offers\Resources\OffersResource;

class EngagementsHelper
{
    use ModelRelationValue;

    /**
     * @param int $id
     *
     * @return null|EngagementRing
     */
    public function getEngagementRing(int $id): ?EngagementRing
    {
        return EngagementRing::withCalculatedPrice()
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @param null  $shape_id
     * @param null  $stoneCarat
     *
     * @return EngagementRingGroupCollection
     */
    public function getEngagementRings($shape_id = null, $stoneCarat = null): EngagementRingGroupCollection
    {
        $request = request();
        $per_page = $request->input('per_page') ?? 20;
        $ring_size = $request->get('ring_size');

        /** @var Builder $filtered_by_price */
        $filtered_by_price = EngagementRing::withCalculatedPrice(['id']);
        FilterBuilderHelper::applyFilter([
            FilterHavingWithRange::make($filtered_by_price, 'calculated_price', $request->get('price')),
            FilterWithProperty::make($filtered_by_price, 'is_active', true),
        ]);
        $filtered_by_price = $filtered_by_price->toBase()->pluck('id');

        /** @var Builder $engagements */
        $engagements = EngagementRing::selectRaw('MAX(is_top) AS is_top, MAX(custom_sort) as custom_sort, `item_name`,`group_sku`,`stone_size`, GROUP_CONCAT(`id`) as ids, ' . $this->priceCalculate($request) . ' as `calculated_price`')
            ->whereIn('id', $filtered_by_price);

        if ($request->get('gender')) {
            $gender = $request->get('gender');
            // костыль для Артемки
            $availableGender = [
                'female' => 'f',
                'male' => 'm'
            ];
            FilterBuilderHelper::applyFilter([
                FilterWithProperty::make($engagements, 'gender', ['n', $availableGender[$gender] ?? 'n']),
            ]);
        }
        FilterBuilderHelper::applyFilter([
            FilterWithRelationSlug::make($engagements, 'metal', $request->get('metal')),
            FilterWithRelationSlug::make($engagements, 'ringStyle', $request->get('style')),
            FilterWithRelationSlug::make($engagements, 'offers', $request->get('offers')),
            FilterWithRelationSlugCondition::make($engagements, 'minRingSize', $ring_size, '<='),
            FilterWithRelationSlugCondition::make($engagements, 'maxRingSize', $ring_size, '>='),
            FilterWithRelationSlug::make($engagements, 'ringCollection', $request->get('collection')),
        ]);

        if ($shape_id && $stoneCarat) {
            FilterBuilderHelper::applyFilter([
                FilterWithProperty::make($engagements, 'min_stone_carat', $stoneCarat, '<='),
                FilterWithProperty::make($engagements, 'max_stone_carat', $stoneCarat, '>='),
                FilterWithProperty::make($engagements, 'stone_shape_id', $shape_id),
            ]);
        } else {
            FilterBuilderHelper::applyFilter([
                FilterWithRelationSlug::make($engagements, 'stoneShape', $request->get('shape')),
            ]);
        }
        FilterBuilderHelper::applyFilter([
            FilterWithProperty::make($engagements, 'stone_size', $request->input('center_stone_size')),
        ]);

        $engagements->orderByDesc('is_top');

        $sort = $request->input('sort.field', 'price');

        switch ($sort) {
            case 'price':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $engagements,
                        true,
                        'calculated_price',
                        $request->input('sort.order', 'asc')
                    ),
                ]);
                break;
            case 'new':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $engagements,
                        true,
                        'custom_sort',
                        'desc'
                    ),
                    SortWithProperty::make(
                        $engagements,
                        true,
                        'calculated_price',
                        'asc'
                    ),
                ]);
                break;
        }
        return new EngagementRingGroupCollection($engagements
            ->groupBy('group_sku')
            ->toBase()
            ->paginate($per_page));
    }

    /**
     * @param $request
     *
     * @return string
     */
    private function priceCalculate(Request $request): string
    {
        if ($request->input('sort.field') == 'price') {
            if ($request->input('sort.order') == 'asc') {
                return 'MIN((
                            CASE
                                WHEN discount_price IS NOT NULL THEN `discount_price`
                                ELSE `raw_price`
                            END
                ))';
            }
            if ($request->input('sort.order') == 'desc') {
                return 'MAX((
                            CASE
                                WHEN discount_price IS NOT NULL THEN `discount_price`
                                ELSE `raw_price`
                            END
                ))';
            }
        }

        return "raw_price";
    }

    /**
     * @param Request $request
     *
     * @return EngagementRing
     */
    public function createEngagement(Request $request): ?EngagementRing
    {
        $engagement = EngagementRing::create([
            'item_name'          => [
                'en' => $request->get('item_name'),
            ],
            'sku'                => $request->get('sku'),
            'approx_stones'      => $request->get('approx_stones'),
            'slug'               => $request->get('slug'),
            'cost_price'         => $request->get('cost_price'),
            'raw_price'          => $request->get('raw_price'),
            'band_width'         => $request->get('band_width'),
            'carat_weight'       => $request->get('carat_weight'),
            'ring_collection_id' => $this->getRelationValue(
                RingCollection::class,
                $request->get('ring_collection')
            ),
            'ring_style_id'      => $this->getRelationValue(
                EngagementRingStyle::class,
                $request->get('ring_style')
            ),
            'stone_shape_id'     => $this->getRelationValue(
                Shape::class,
                $request->get('stone_shape')
            ),
            'stone_size'         => $request->get('stone_size'),
            'setting_type'       => $request->get('setting_type'),
            'side_setting_type'  => $request->get('side_setting_type'),
            'min_ring_size_id'   => $this->getRelationValue(
                RingSize::class,
                $request->get('min_ring_size')
            ),
            'max_ring_size_id'   => $this->getRelationValue(
                RingSize::class,
                $request->get('max_ring_size')
            ),
            'metal_id'           => $this->getRelationValue(
                Metal::class,
                $request->get('metal')
            ),
            'average_ss_colour' => $request->get('average_ss_colour'),
            'average_ss_clarity' => $request->get('average_ss_clarity'),
        ]);

        $images = $request->file('image');
        if (is_array($images)) {
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $engagement
                        ->addMedia($image)
                        ->withResponsiveImages()
                        ->toMediaCollection('engagement-images');

                } else {
                    Log::channel('fail-engagement')->info('Undefined image: ' . $request->get('sku'));
                }
            }
        }

        $video = $request->file('video');
        if ($video instanceof UploadedFile) {
            $engagement
                ->addMedia($video)
                ->toMediaCollection('engagement-video');
        }

        Log::channel('success-engagement')->info($request->get('sku'));

        return $engagement;
    }

    /**
     * @param string  $sku
     * @param Request $request
     *
     * @return EngagementRing
     */
    public function updateEngagement(string $sku, Request $request): EngagementRing
    {
        /** @var EngagementRing $diamond */
        $engagement = EngagementRing::withCalculatedPrice()
            ->where(['sku' => $sku])
            ->firstOrFail();

        if ($request->has('slug')) {
            $engagement->slug = $request->get('slug');
        }

        if ($request->has('approx_stones')) {
            $engagement->approx_stones = $request->get('approx_stones');
        }

        if ($request->has('band_width')) {
            $engagement->band_width = $request->get('band_width');
        }

        if ($request->has('raw_price')) {
            $engagement->raw_price = $request->get('raw_price');
        }

        if ($request->has('inc_price')) {
            $engagement->inc_price = $request->get('inc_price');
        }

        // Filling relations
        if ($request->has('ring_collection')) {
            $this->associateRelation(
                $engagement->ringCollection(),
                RingCollection::class,
                $request->get('ring_collection')
            );
        }

        if ($request->has('stone_shape')) {
            $this->associateRelation(
                $engagement->stoneShape(),
                Shape::class,
                $request->get('stone_shape')
            );
        }

        if ($request->has('carat_weight')) {
            $engagement->carat_weight = $request->get('carat_weight');
        }
        if ($request->has('average_ss_colour')) {
            $engagement->average_ss_colour = $request->get('average_ss_colour');
        }
        if ($request->has('average_ss_clarity')) {
            $engagement->average_ss_clarity = $request->get('average_ss_clarity');
        }

        $engagement->save();

        $images = $request->file('image');
        if (is_array($images)) {
            $engagement->clearMediaCollection('engagement-images');
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $engagement
                        ->addMedia($image)
                        ->withResponsiveImages()
                        ->toMediaCollection('engagement-images');

                } else {
                    Log::channel('fail-engagement')->info('Undefined image: ' . $request->get('sku'));
                }
            }
        }

        $video = $request->file('video');
        if ($video instanceof UploadedFile) {
            $engagement->clearMediaCollection('engagement-video');
            $engagement
                ->addMedia($video)
                ->toMediaCollection('engagement-video');
        }

        return $engagement;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
                'metal'      => MetalResource::collection(Metal::where('engagement_off', false)->get()),
                'price'      => [
                    'min' => EngagementRing::withMinCalculatedPrice()->first()->min_calculated_price,
                    'max' => EngagementRing::withMaxCalculatedPrice()->first()->max_calculated_price,
                ],
                'style'      => RingStyleResource::collection(EngagementRingStyle::all()),
                'size'       => RingSizeResource::collection(RingSize::all()),
                'collection' => RingCollectionResource::collection(RingCollection::all()),
                'offers'     => OffersResource::collection(Offer::withActiveOrder()->get()),
            ];
    }

    /**
     * @param Relation    $relation
     * @param string      $relationClass
     * @param null|string $slug
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

    /**
     * @param $item
     *
     * @return mixed
     */
    public function getRingShapesSKU($item)
    {
        $shape_sku = explode('/', $item->group_sku);
        $shape_sku[1] = '%';
        return EngagementRing::withCalculatedPrice()
            ->where('group_sku', 'like', implode('/', $shape_sku))
            ->where('metal_id', $item->metal_id)
            ->where('stone_shape_id', '!=', $item->stone_shape_id)
            ->get();
    }
}