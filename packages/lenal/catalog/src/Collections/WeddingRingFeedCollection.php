<?php

namespace lenal\catalog\Collections;


use Illuminate\Support\Collection;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Repositories\RingStyleRepository;
use lenal\catalog\Resources\ImageModernResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * @property WeddingRing[] $items
 */
class WeddingRingFeedCollection extends Collection
{
    public function toArray()
    {
        $metals = new MetalRepository();
        $ringStyles = new RingStyleRepository(RingStyleRepository::TYPE_WEDDING);
        $favorites = new FavoritesCompareHelper();

        $sizes = RingSize::asArray();

        $response = [];

        foreach ($this->items as $ring) {
            $images = $ring->cachedImages();
            $image = $ring->getFirstMedia('img-wedding');
            $imageResource = null;
            if ($image) {
                $imageResource = new ImageModernResource($image, $ring->formats, $ring->feedConversions);
            }

            $response[] = [
                'id'            => $ring->id,
                'title'         => $ring->title,
                'subtitle'      => $ring->subtitle,
                'h1'            => $ring->h1,
                'h2'            => $ring->h2,
                'slug'          => $ring->slug,
                'sku'           => $ring->sku,
                'group_sku'     => $ring->group_sku,
                'image'         => $imageResource,
                'preview_image' => $images['preview'] ?? null,
                'price'         => [
                    'old_count' => $ring->old_calculated_price,
                    'count'     => $ring->calculated_price,
                    'currency'  => CurrencyRate::getUserCurrency(),
                ],
                'options'       => [
                    'carat_weight'  => $ring->carat_weight,
                    'gender'            => $ring->gender,
                    'band_width'        => [
                        'count'     => (float) $ring->band_width,
                        'dimension' => 'mm',
                    ],
                    'ring_style'        => $ringStyles->get($ring->ring_style_id),
                    'ring_collection'   => $ring->ringCollection ? $ring->ringCollection->toShortArray() : null,
                    'side_setting_type' => $ring->side_setting_type,
                    'min_ring_size'     => $ring->minRingSize->toArray() ?? null,
                    'max_ring_size'     => $ring->maxRingSize->toArray() ?? null,
                    'metal'             => $metals->get($ring->metal_id),
                    'offers'            => $ring->findOffers(),
                    'approx_stones'     => $ring->approx_stones,
                ],
                'in_favorites'  => $favorites->inFavorites($ring->id, WeddingRing::class),
                'in_compares'   => $favorites->inCompares($ring->id, WeddingRing::class),
                'product_type'  => 'wedding-rings',
                'selected_size' => $sizes[$ring->size_slug] ?? null,
                'in_store' => $ring->in_store
            ];
        }

        return $response;
    }
}