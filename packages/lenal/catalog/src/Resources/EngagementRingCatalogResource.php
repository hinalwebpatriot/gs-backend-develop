<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\RingSize;
use lenal\offers\Resources\OffersResource;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\reviews\Facades\ReviewsHelper;

/**
 * Class DiamondResource
 *
 * @package lenal\catalog\Resources
 */
class EngagementRingCatalogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $images = $this->resource
            ? $this->getMedia('engagement-images')
            : collect();

        $rate = ReviewsHelper::getProductRate($this->resource);

        return [
            'id'            => $this->engagement_ring_id ?? $this->id,
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            "h1"            => $this->h1,
            "h2"            => $this->h2,
            'slug'          => $this->slug,
            'sku'           => $this->sku,
            'group_sku'     => $this->group_sku,
            'preview_image' => new ImageResource($images->first()),
            'price'         => [
                'old_count' => $this->old_calculated_price,
                'count'     => $this->calculated_price,
                'currency'  => CurrencyRate::getUserCurrency(),
            ],
            'options'       => [
                'carat_weight'      => $this->carat_weight,
                'band_width'        => [
                    'count'     => (float)$this->band_width,
                    'dimension' => 'mm',
                ],
                'ring_collection'   => new RingCollectionResource($this->ringCollection),
                'stone_size'        => [
                    'count'     => (float)$this->stone_size,
                    'dimension' => 'ct'
                ],
                'setting_type'      => $this->setting_type,
                'side_setting_type' => $this->side_setting_type,
                'min_ring_size'     => new RingSizeResource($this->minRingSize),
                'max_ring_size'     => new RingSizeResource($this->maxRingSize),
                'metal'             => new MetalResource($this->metal),
                'offers'            => OffersResource::collection($this->offers),
                'average_ss_colour' => $this->average_ss_colour,
                'average_ss_clarity'=> $this->average_ss_clarity,
                'approx_stones'     => $this->approx_stones,
            ],
            'in_favorites'  => FavoritesCompareHelper::inFavorites($this->id, EngagementRing::class),
            'in_compares'   => FavoritesCompareHelper::inCompares($this->id, EngagementRing::class),
            'product_type'  => 'engagement-rings',
            'rate' => $rate['average'],
            'reviews_count' => $rate['count'],
            'selected_size' => $this->size_slug
                ? new RingSizeResource(RingSize::where('slug', $this->size_slug)->first())
                : null

        ];
    }
}
