<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\RingSize;
use lenal\offers\Resources\OffersResource;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\reviews\Facades\ReviewsHelper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class DiamondResource
 *
 * @package lenal\catalog\Resources
 * @mixin EngagementRing
 */
class EngagementRingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $images = $this->resource
            ? $this->getMedia('engagement-images')
            : collect();
        $modernImages = $this->resource
            ? $this->getMedia('img-engagement')
            : collect();
        $image = $modernImages->first();
        $imageResource = null;
        if ($image) {
            $imageResource = new ImageModernResource($image, $this->formats, $this->feedConversions);
        }
        $modernImages = $modernImages->map(function (Media $item) {
           return new ImageModernResource($item, $this->formats, $this->cardConversions);
        });
        $video = $this->resource
            ? $this->getMedia('engagement-video')->first()
            : null;
        $images360 = $this->getMedia('engagement-images-3d')->count()
            ? $this->getMedia('engagement-images-3d')->map(function ($media) {
                return $media->getFullUrl();
            })
            : null;
        $rate = ReviewsHelper::getProductRate($this->resource);

        return [
            'id'                  => $this->engagement_ring_id ?? $this->id,
            'title'               => $this->title,
            'subtitle'            => $this->subtitle,
            'header'              => $this->header ?: $this->h1,
            "h1"                  => $this->h1,
            "h2"                  => $this->h2,
            'slug'                => $this->slug,
            'sku'                 => $this->sku,
            'group_sku'           => $this->group_sku,
            'preview_image'       => new ImageResource($images->first()),
            'image'         => $imageResource,
            'images'              => ImageResource::collection($images),
            'pictures'      => $modernImages,
            'images_360'          => $images360,
            'video'               => new VideoResource($video),
            'price'               => [
                'old_count' => $this->old_calculated_price,
                'count'     => $this->calculated_price,
                'currency'  => CurrencyRate::getUserCurrency(),
            ],
            'description'         => $this->description ?: ($this->ringCollection->description ?? ''),
            'options'             => [
                'carat_weight'       => $this->carat_weight,
                'band_width'         => [
                    'count'     => (float) $this->band_width,
                    'dimension' => 'mm',
                ],
                'ring_collection'    => new RingCollectionResource($this->ringCollection),
                'ring_style'         => new RingStyleResource($this->ringStyle),
                'stone_shape'        => new ShapeResource($this->stoneShape),
                'stone_size'         => [
                    'count'     => (float) $this->stone_size,
                    'dimension' => 'ct'
                ],
                'setting_type'       => $this->setting_type,
                'side_setting_type'  => $this->side_setting_type,
                'min_ring_size'      => new RingSizeResource($this->minRingSize),
                'max_ring_size'      => new RingSizeResource($this->maxRingSize),
                'metal'              => new MetalResource($this->metal),
                'offers'             => OffersResource::collection($this->offers),
                'average_ss_colour'  => $this->average_ss_colour,
                'average_ss_clarity' => $this->average_ss_clarity,
                'approx_stones'      => $this->approx_stones,
                'gender'             => $this->gender,
                'min_stone_carat'    => $this->min_stone_carat,
                'max_stone_carat'    => $this->max_stone_carat,
            ],
            'in_favorites'        => FavoritesCompareHelper::inFavorites($this->id, EngagementRing::class),
            'in_compares'         => FavoritesCompareHelper::inCompares($this->id, EngagementRing::class),
            'product_type'        => 'engagement-rings',
            'rate'                => $rate['average'],
            'reviews_count'       => $rate['count'],
            'delivery_period'     => $this->estimateDeliveryTime(),
            'delivery_to'         => $this->getEstimateDeliveryTime(),
            'selected_size'       => $this->size_slug
                ? new RingSizeResource(RingSize::query()->where('slug', $this->size_slug)->first())
                : null,
            'disable_constructor' => $this->disable_constructor,
            'in_store'            => $this->in_store,
            'is_available'        => $this->isAvailable(),
            'custom_fields'       => CustomFieldResource::collection($this->customFields),
        ];
    }
}
