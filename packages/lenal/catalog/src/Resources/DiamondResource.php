<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class DiamondResource
 *
 * @package lenal\catalog\Resources
 * @mixin Diamond
 */
class DiamondResource extends JsonResource
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
            ? $this->getMedia('diamond-images')
            : collect();

        $video = null;
        if ($this->video && $this->video != '-') {
            $video = $this->video;
        }

        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'subtitle'           => $this->subtitle,
            'slug'               => $this->slug,
            'sku'                => $this->sku,
            'stock_number'       => $this->stock_number,
            'certificate_number' => $this->certificate_number,
            'preview_image'      => $this->defaultImage(),
            'images'             => $images->count() == 0
                ? [$this->defaultImage()]
                : ImageResource::collection($images),
            'price'              => [
                'old_count' => $this->old_calculated_price
                    ? round($this->old_calculated_price, 2)
                    : null,
                'count' => round($this->calculated_price, 2),
                'currency' => CurrencyRate::getUserCurrency(),
                'flash_sale_percent' => 100 - config('price_calculate.diamonds_price_update', 1) * 100
            ],
            'options'            => [
                'carat'        => (string)number_format($this->carat, 2, '.',''),
                'depth'        => $this->depth,
                'table'        => $this->table,
                'girdle'       => $this->girdle,
                'video'        => $video,
                'certificate'  => $this->certificate ? $this->certificate : null,
                'dimensions'   => $this->length . ' - ' . $this->width . ' x ' . $this->height,
                'size_ratio'   => $this->size_ratio,
                'manufacturer' => $this->manufacturer,
                'shape' => $this->parseOption($this->shape),
                'color' => $this->color,
                'cut' => $this->parseOption($this->cut),
                'polish' => $this->parseOption($this->polish),
                'symmetry' => $this->parseOption($this->symmetry),
                'fluorescence' => $this->parseOption($this->fluorescence),
                'clarity' => $this->clarity,
                'culet' => $this->parseOption($this->culet),
            ],
            'in_favorites' => FavoritesCompareHelper::inFavorites($this->id, Diamond::class),
            'in_compares' => FavoritesCompareHelper::inCompares($this->id, Diamond::class),
            'product_type' => 'diamonds',
            'delivery_to' => $this->getEstimateDeliveryTime(),
            'in_store' => $this->in_store,
            'is_available' => $this->isAvailable(),
        ];
    }

    /**
     * @param $option
     *
     * @return array|null
     */
    protected function parseOption($option)
    {
        if ($option !== null) {
            return [
                'id'    => $option->id,
                'title' => $option->title,
                'slug'  => $option->slug,
            ];
        }

        return null;
    }
}
