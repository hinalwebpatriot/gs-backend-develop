<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\offers\Resources\OffersResource;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\reviews\Facades\ReviewsHelper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class DiamondResource
 *
 * @package lenal\catalog\Resources
 * @mixin  WeddingRing
 */
class WeddingRingResource extends JsonResource
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
            ? $this->getMedia('wedding-images')
            : collect();
        $modernImages = $this->resource
            ? $this->getMedia('img-wedding')
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
            ? $this->getMedia('wedding-video')->first()
            : null;
        $images360 = $this->getMedia('wedding-images-3d')->count()
            ? $this->getMedia('wedding-images-3d')->map(function ($media) {
                return $media->getFullUrl();
            })
            : null;
        $rate = ReviewsHelper::getProductRate($this->resource);
        $video360 = $this->resource
            ? $this->getMedia('wedding-video-360')->first()
            : null;


        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'subtitle'        => $this->subtitle,
            'h1'              => $this->h1,
            'h2'              => $this->h2,
            'header'          => $this->header ?: $this->h1,
            'slug'            => $this->slug,
            'sku'             => $this->sku,
            'group_sku'       => $this->group_sku,
            'preview_image'   => new ImageResource($images->first()),
            'image'           => $imageResource,
            'images'          => ImageResource::collection($images),
            'pictures'        => $modernImages,
            'images_360'      => $images360,
            'video'           => new VideoResource($video),
            'price'           => [
                'old_count' => $this->old_calculated_price,
                'count'     => $this->calculated_price,
                'currency'  => CurrencyRate::getUserCurrency(),
            ],
            'description'     => $this->description ?: ($this->ringCollection->description ?? ''),
            'options'         => [
                'carat_weight'      => $this->carat_weight,
                'gender'            => $this->gender,
                'band_width'        => $this->band_width ? [
                    'count'     => (float) $this->band_width,
                    'dimension' => 'mm',
                ] : null,
                'thickness'         => [
                    'count'     => (float) $this->thickness,
                    'dimension' => 'mm',
                ],
                'ring_style'        => new RingStyleResource($this->ringStyle),
                'ring_collection'   => new RingCollectionResource($this->ringCollection),
                'side_setting_type' => $this->side_setting_type,
                'min_ring_size'     => new RingSizeResource($this->minRingSize),
                'max_ring_size'     => new RingSizeResource($this->maxRingSize),
                'metal'             => new MetalResource($this->metal),
                'offers'            => OffersResource::collection($this->offers),
                'approx_stones'     => $this->approx_stones,
            ],
            'in_favorites'    => FavoritesCompareHelper::inFavorites($this->id, WeddingRing::class),
            'in_compares'     => FavoritesCompareHelper::inCompares($this->id, WeddingRing::class),
            'product_type'    => 'wedding-rings',
            'rate'            => $rate['average'],
            'reviews_count'   => $rate['count'],
            'selected_size'   => $this->size_slug
                ? new RingSizeResource(RingSize::where('slug', $this->size_slug)->first())
                : null,
            'video_360'       => new VideoResource($video360),
            'delivery_period' => $this->estimateDeliveryTime(),
            'delivery_to'     => $this->getEstimateDeliveryTime(),
            'in_store'        => $this->in_store,
            'is_available'    => $this->isAvailable(),
            'custom_fields'   => CustomFieldResource::collection($this->customFields),
        ];
    }
}
