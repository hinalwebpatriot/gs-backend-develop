<?php

namespace lenal\catalog\Collections;


use Illuminate\Support\Collection;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Resources\ImageModernResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class EngagementRingFeedResponse
 * @property EngagementRing[] $items
 */
class EngagementRingFeedCollection extends Collection
{
    public function toArray()
    {
        $metals = new MetalRepository();
        $favorites = new FavoritesCompareHelper();
        $response = [];

        foreach ($this->items as $ring) {
            $images = $ring->cachedImages();
            $image = $ring->getFirstMedia('img-engagement');
            $imageResource = null;
            if ($image) {
                $imageResource = new ImageModernResource($image, $ring->formats, $ring->feedConversions);
            }

            $response[] = [
                'id'                  => $ring->id,
                'title'               => $ring->title,
                'subtitle'            => $ring->subtitle,
                'h1'                  => $ring->h1,
                'h2'                  => $ring->h2,
                'slug'                => $ring->slug,
                'sku'                 => $ring->sku,
                'group_sku'           => $ring->group_sku,
                'image'               => $imageResource,
                'preview_image'       => $images['preview'] ?? null,
                'price'               => [
                    'old_count' => $ring->old_calculated_price,
                    'count'     => $ring->calculated_price,
                    'currency'  => CurrencyRate::getUserCurrency(),
                ],
                'options'             => [
                    'carat_weight'       => $ring->carat_weight,
                    'band_width'         => [
                        'count'     => (float) $ring->band_width,
                        'dimension' => 'mm',
                    ],
                    'ring_collection'    => $ring->ringCollection ? $ring->ringCollection->toShortArray() : null,
                    'stone_size'         => [
                        'count'     => (float) $ring->stone_size,
                        'dimension' => 'ct'
                    ],
                    'setting_type'       => $ring->setting_type,
                    'side_setting_type'  => $ring->side_setting_type,
                    'min_ring_size'      => $ring->minRingSize->toArray() ?? null,
                    'max_ring_size'      => $ring->maxRingSize->toArray() ?? null,
                    'metal'              => $metals->get($ring->metal_id),
                    'offers'             => $ring->findOffers(),
                    'average_ss_colour'  => $ring->average_ss_colour,
                    'average_ss_clarity' => $ring->average_ss_clarity,
                    'approx_stones'      => $ring->approx_stones,
                    'gender'             => $ring->gender
                ],
                'in_favorites'        => $favorites->inFavorites($ring->id, EngagementRing::class),
                'in_compares'         => $favorites->inCompares($ring->id, EngagementRing::class),
                'product_type'        => 'engagement-rings',
                'selected_size'       => $ring->size_slug ? RingSize::bySlug($ring->size_slug) : null,
                'disable_constructor' => $ring->disable_constructor,
                'in_store'            => $ring->in_store,
            ];
        }

        return $response;
    }
}
