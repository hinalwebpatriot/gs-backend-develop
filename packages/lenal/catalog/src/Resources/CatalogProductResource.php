<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Models\Products\Product;
use lenal\offers\Resources\OffersResource;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\reviews\Facades\ReviewsHelper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property \lenal\catalog\Models\Products\Product $resource
 * @mixin \lenal\catalog\Models\Products\Product
 */
class CatalogProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = $this->resource
            ? $this->getMedia('product-images')
            : collect();
        $modernImages = $this->resource
            ? $this->getMedia('img-product')
            : collect();
        $modernImages = $modernImages->map(function (Media $item) {
            return new ImageModernResource($item, $this->formats, $this->cardConversions);
        });

        $video = $this->resource
            ? $this->getMedia('product-video')->first()
            : null;

        $images360 = $this->getMedia('product-images-3d')->count()
            ? $this->getMedia('product-images-3d')->map(function (Media $media) {
                return $media->getFullUrl();
            })
            : null;

        $rate = ReviewsHelper::getProductRate($this->resource);

        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'subtitle'        => $this->subTitle,
            'header'          => $this->header ?: $this->h1,
            'h1'              => $this->h1,
            'h2'              => $this->h2,
            'slug'            => $this->slug,
            'sku'             => $this->sku,
            'group_sku'       => $this->group_sku,
            'preview_image'   => new ImageResource($images->first()),
            'pictures'        => $modernImages,
            'images'          => ImageResource::collection($images),
            'images_360'      => $images360,
            'video'           => new VideoResource($video),
            'category'        => $this->category->map(),
            'price'           => [
                'old_count' => $this->old_calculated_price,
                'count'     => $this->calculated_price,
                'currency'  => CurrencyRate::getUserCurrency(),
            ],
            'description'     => $this->description ?: ($this->brand->description ?? ''),
            'options'         => [
                'carat_weight'            => $this->carat_weight,
                'band_width'              => [
                    'count'     => (float) $this->band_width,
                    'dimension' => 'mm',
                ],
                'brand'                   => new BrandResource($this->brand),
                'style'                   => new ProductStyleResource($this->style),
                'stone_shape'             => $this->stoneShape ? new ShapeResource($this->stoneShape) : [],
                'stone_size'              => [
                    'count'     => (float) $this->stone_size,
                    'dimension' => 'ct'
                ],
                'text_for_center_stone'   => $this->is_include_center_stone
                    ? ($this->text_for_center_stone ?: null)
                    : null,
                'is_include_center_stone' => (boolean) $this->is_include_center_stone,
                'setting_type'            => $this->setting_type,
                'side_setting_type'       => $this->side_setting_type,
                'min_size'                => new ProductSizeResource($this->minSize),
                'max_size'                => new ProductSizeResource($this->maxSize),
                'metal'                   => new MetalResource($this->metal),
                'offers'                  => OffersResource::collection($this->offers),
                'average_ss_colour'       => $this->average_ss_colour,
                'average_ss_clarity'      => $this->average_ss_clarity,
                'approx_stones'           => $this->approx_stones,
            ],
            'in_favorites'    => FavoritesCompareHelper::inFavorites($this->id, Product::class),
            'in_compares'     => FavoritesCompareHelper::inCompares($this->id, Product::class),
            'product_type'    => 'products',
            'rate'            => $rate['average'],
            'reviews_count'   => $rate['count'],
            'selected_size'   => null,
            'is_sold_out'     => $this->is_sold_out,
            'sold_out_title'  => $this->getSoldOutTitle(),
            'delivery_period' => $this->estimateDeliveryTime(),
            'delivery_to'     => $this->getEstimateDeliveryTime(),
            'in_store'        => $this->in_store,
            'custom_fields'   => CustomFieldResource::collection($this->customFields),
        ];
    }
}
