<?php

namespace lenal\catalog\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use lenal\catalog\Helpers\FilterTypes\FilterHavingWithRange;
use lenal\catalog\Helpers\FilterTypes\FilterWithProperty;
use lenal\catalog\Helpers\FilterTypes\FilterWithRelationSlugCondition;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\FilterDescription;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Helpers\FilterTypes\FilterWithRelationSlug;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Resources\FilterDescriptionCollection;
use lenal\catalog\Resources\MetalResource;
use lenal\catalog\Resources\RingSizeResource;
use lenal\catalog\Resources\RingStyleResource;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRingStyle;
use lenal\catalog\Resources\WeddingRingGroupCollection;
use lenal\catalog\Resources\WeddingRingResource;
use lenal\offers\Models\Offer;
use lenal\offers\Resources\OffersResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class WeddingsHelper
 *
 * @package lenal\catalog\Helpers
 */
class WeddingsHelper
{
    use ModelRelationValue;

    /**
     * @param Request $request
     *
     * @return WeddingRingGroupCollection
     */
    public function getWeddingRings(Request $request)
    {
        $per_page = request()->input('per_page') ?? 20;
        $ring_size = $request->get('ring_size');

        $filtered_by_price = WeddingRing::withCalculatedPrice(['id']);
        FilterBuilderHelper::applyFilter([
            FilterHavingWithRange::make($filtered_by_price, 'calculated_price', $request->get('price')),
            FilterWithProperty::make($filtered_by_price, 'is_active', true),
        ]);
        $filtered_by_price = $filtered_by_price->toBase()->pluck('id');

        $weddings = WeddingRing::selectRaw('MAX(is_top) AS is_top, MAX(custom_sort) as custom_sort,`group_sku`, GROUP_CONCAT(`id`) as ids, ' . $this->priceCalculate($request) . ' as `calculated_price`')
            ->whereIn('id', $filtered_by_price);

        FilterBuilderHelper::applyFilter([
            FilterWithProperty::make($weddings, 'gender', $request->get('gender')),
            FilterWithRelationSlug::make($weddings, 'ringStyle', $request->get('style')),
            FilterWithRelationSlug::make($weddings, 'metal', $request->get('metal')),
            FilterWithRelationSlug::make($weddings, 'offers', $request->get('offers')),
            FilterWithRelationSlugCondition::make($weddings, 'minRingSize', $ring_size, '<='),
            FilterWithRelationSlugCondition::make($weddings, 'maxRingSize', $ring_size, '>='),
        ]);


        $sort = $request->input('sort.field', 'price');

        switch ($sort) {
            case 'price':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $weddings,
                        true,
                        'calculated_price',
                        $request->input('sort.order', 'asc')
                    ),
                ]);
                break;
            case 'new':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $weddings,
                        true,
                        'custom_sort',
                        'desc'
                    ),
                    SortWithProperty::make(
                        $weddings,
                        true,
                        'calculated_price',
                        'asc'
                    ),
                ]);
                break;
        }

        return new WeddingRingGroupCollection($weddings
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
        $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency());

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

        return "CEIL(raw_price/$rate)";
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getWeddingRing(int $id)
    {
        return WeddingRing::withCalculatedPrice()
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @param Request $request
     *
     * @return WeddingRing
     */
    public function createWedding(Request $request): ?WeddingRing
    {
        $wedding = WeddingRing::create([
            'item_name'          => [
                'en' => $request->get('item_name'),
            ],
            'sku'                => $request->get('sku'),
            'approx_stones'      => $request->get('approx_stones'),
            'slug'               => $request->get('slug'),
            'cost_price'         => $request->get('cost_price'),
            'raw_price'          => $request->get('raw_price'),
            'inc_price'          => $request->get('inc_price'),
            'band_width'         => $request->get('band_width'),
            'gender'             => $request->get('gender'),
            'carat_weight'       => $request->get('carat_weight'),
            'thickness'          => $request->get('thickness'),
            'ring_collection_id' => $this->getRelationValue(
                RingCollection::class,
                $request->get('ring_collection')
            ),
            'ring_style_id'      => $this->getRelationValue(
                WeddingRingStyle::class,
                $request->get('ring_style')
            ),
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
        ]);

        $images = $request->file('image');
        if (is_array($images)) {
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $wedding
                        ->addMedia($image)
                        ->withResponsiveImages()
                        ->toMediaCollection('wedding-images');
                } else {
                    Log::channel('fail-engagement')->info('Undefined image: ' . $request->get('sku'));
                }
            }
        }

        $video = $request->file('video');
        if ($video instanceof UploadedFile) {
            $wedding
                ->addMedia($video)
                ->toMediaCollection('wedding-video');
        }

        Log::channel('success-wedding')->info($request->get('sku'));

        return $wedding;
    }

    /**
     * @param string $sku
     * @param Request $request
     *
     * @return WeddingRing
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateWedding(string $sku, Request $request): WeddingRing
    {
        /** @var WeddingRing $wedding */
        $wedding = WeddingRing::withCalculatedPrice()
            ->where(['sku' => $sku])
            ->firstOrFail();

        if ($request->has('slug')) {
            $wedding->slug = $request->get('slug');
        }

        if ($request->has('approx_stones')) {
            $wedding->approx_stones = $request->get('approx_stones');
        }

        if ($request->has('band_width')) {
            $wedding->band_width = $request->get('band_width');
        }

        if ($request->has('gender')) {
            $wedding->raw_price = $request->get('gender');
        }

        if ($request->has('raw_price')) {
            $wedding->raw_price = $request->get('raw_price');
        }

        if ($request->has('inc_price')) {
            $wedding->inc_price = $request->get('inc_price');
        }

        // Filling relations
        if ($request->has('ring_collection')) {
            $this->associateRelation(
                $wedding->ringCollection(),
                RingCollection::class,
                $request->get('ring_collection')
            );
        }

        if ($request->has('carat_weight')) {
            $wedding->carat_weight = $request->get('carat_weight');
        }

        if ($request->has('metal')) {
            $wedding->metal_id = $this->getRelationValue(Metal::class, $request->get('metal'));
        }

        $wedding->save();

        $images = $request->file('image');
        if (is_array($images)) {
            $wedding->clearMediaCollection('wedding-images');
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $wedding
                        ->addMedia($image)
                        ->withResponsiveImages()
                        ->toMediaCollection('wedding-images');
                } else {
                    Log::channel('fail-engagement')->info('Undefined image: ' . $request->get('sku'));
                }
            }
        }

        $video = $request->file('video');
        if ($video instanceof UploadedFile) {
            $wedding->clearMediaCollection('wedding-video');
            $wedding
                ->addMedia($video)
                ->toMediaCollection('wedding-video');
        }

        return $wedding;
    }

    public function importImages(WeddingRing $model, $folder, $filename)
    {
        $localStorage = Storage::disk('local');
        $dirPath = 'tmp/import/' . $folder;
        $image = $dirPath . '/' . $filename;

        if (!$localStorage->exists($image)) {
            return ;
        }

        try {
            $model->addMedia($localStorage->path($image))
                ->withResponsiveImages()
                ->toMediaCollection('wedding-images');

            $localStorage->delete($image);

            if (!count($localStorage->files($dirPath))) {
                $localStorage->deleteDirectory($dirPath);
            }
        } catch (Exception $e) {
            logger()->channel('fail-wedding')->error($e->getMessage() . '. Cannot save file to local storage with path: ' . $image);
        }
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

    public function moreMetalsSlider($ring_id)
    {
        $ring = WeddingRing::find($ring_id);
        if ($ring && $ring->ring_collection_id && $ring->metal && $ring->raw_price) {
            $items = WeddingRing
                ::withCalculatedPrice()
                ->where('ring_collection_id', '=', $ring->ring_collection_id)
                ->where('metal_id', '!=', $ring->metal_id)
                ->whereBetween('raw_price', [$ring->raw_price * 0.8, $ring->raw_price * 1.2])
                ->take(10)
                ->get();

            if ($items->count()) {
                return WeddingRingResource::collection($items);
            }
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function similarCollectionsSlider($ring_id)
    {
        /*$ring = WeddingRing::find($ring_id);
        if ($ring && $ring->ring_collection_id && $ring->ring_style_id && $ring->raw_price) {
            $items = WeddingRing
                ::select(DB::raw('tmp.*'))
                ->leftJoin(DB::raw('(' . WeddingRing::withCalculatedPrice()->inRandomOrder()->toSql() . ') tmp'), 'wedding_rings.item_name', 'tmp.item_name')
                ->where('tmp.group_sku', '!=', $ring->group_sku)
                ->where('tmp.ring_collection_id', '!=', $ring->ring_collection_id)
                ->whereBetween('tmp.raw_price', [$ring->raw_price * 0.8, $ring->raw_price * 1.2])
                ->inRandomOrder()
                ->groupBy('tmp.ring_collection_id')
                ->take(10)
                ->get();

            if ($items->count()) {
                return WeddingRingResource::collection($items);
            }
        }*/
        $ring = WeddingRing::withCalculatedPrice()->where('id', $ring_id)->first();
        if ($ring && $ring->ring_collection_id && $ring->calculated_price) {
            $price = $ring->calculated_price;
            $items = WeddingRing
                ::withCalculatedPrice()
                ->where('ring_collection_id', '!=', $ring->ring_collection_id)
                ->inRandomOrder()
                ->get()
                ->filter(function ($item) use ($price) { // apply price range
                    return ($item->calculated_price >= $price * 0.8 && $item->calculated_price <= $price * 1.2);
                })
                ->take(10);
            if ($items->count()) {
                return WeddingRingResource::collection($items);
            }
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            'gender' => [
                'male',
                'female',
            ],
            'style'  => RingStyleResource::collection(WeddingRingStyle::all()),
            'metal'  => MetalResource::collection(Metal::all()),
            'price'  => [
                'min' => WeddingRing::withMinCalculatedPrice()->first()->min_calculated_price,
                'max' => WeddingRing::withMaxCalculatedPrice()->first()->max_calculated_price,
            ],
            'size'   => RingSizeResource::collection(RingSize::all()),
            'offers' => OffersResource::collection(Offer::withActiveOrder()->get()),
        ];
    }

}

