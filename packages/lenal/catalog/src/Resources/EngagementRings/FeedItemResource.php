<?php

namespace lenal\catalog\Resources\EngagementRings;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Resources\ImageModernResource;
use lenal\catalog\Resources\MetalWithoutImageResource;
use lenal\catalog\Resources\OfferResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class BrandResource
 * @mixin \lenal\catalog\Models\Rings\EngagementRing
 */
class FeedItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->firstMedia;
        $favorites = new FavoritesCompareHelper();

        return [
            'id'       => $this->id,
            'sku'      => $this->sku,
            'h1'       => $this->h1,
            'title'    => $this->title,
            'subtitle' => $this->subtitle,
            'image'    => $image ? new ImageModernResource($image, $this->formats, $this->feedConversions) : null,

            'in_favorites' => $favorites->inFavorites($this->id, EngagementRing::class),
            'in_compares'  => $favorites->inCompares($this->id, EngagementRing::class),

            'price' => [
                'count'     => $this->calculated_price,
                'old_count' => $this->old_calculated_price,
                'currency'  => CurrencyRate::getUserCurrency(),
            ],

            'options' => [
                'metal'  => new MetalWithoutImageResource($this->metal),
                'offers' => OfferResource::collection($this->offers)
            ],
        ];
    }
}
