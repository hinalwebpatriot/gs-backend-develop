<?php

namespace lenal\catalog\Collections;


use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Resources\ImageModernResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * @property Product[] $collection
 * @property $size_slug
 */
class ProductFeedCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $metals = new MetalRepository();
        $brands = Brand::asArray();
        $categories = Category::asArray();
        $favorites = new FavoritesCompareHelper();
        $sizes = ProductSize::asArray();

        $sizeCollection = collect($sizes);

        $response = [];

        foreach ($this->collection as $product) {
            $images = $product->cachedImages();
            $category = $categories[$product->category_id] ?? null;
            $image = $product->getFirstMedia('img-product');
            $imageResource = null;
            if ($image) {
                $imageResource = new ImageModernResource($image, $product->formats, $product->feedConversions);
            }

            $response[] = [
                'id'            => $product->id,
                'category'      => $category,
                'title'         => $product->title,
                'subtitle'      => $product->subTitle,
                'h1'            => $product->h1,
                'h2'            => $product->h2,
                'slug'          => $product->slug,
                'sku'           => $product->sku,
                'group_sku'     => $product->group_sku,
                'image'         => $imageResource,
                'preview_image' => $images['preview'] ?? null,
                'price'         => [
                    'old_count' => $product->old_calculated_price,
                    'count'     => $product->calculated_price,
                    'currency'  => CurrencyRate::getUserCurrency(),
                ],
                'options'       => [
                    'carat_weight' => $product->carat_weight,
                    'band_width' => [
                        'count' => (float)$product->band_width,
                        'dimension' => 'mm',
                    ],
                    'brand' => $brands[$product->brand_id] ?? null,
                    'stone_size' => [
                        'count' => (float)$product->stone_size,
                        'dimension' => 'ct'
                    ],
                    'text_for_center_stone' => $product->is_include_center_stone
                        ? ($product->text_for_center_stone ?: null)
                        : null,
                    'is_include_center_stone' => (boolean)$product->is_include_center_stone,
                    'setting_type' => $product->setting_type,
                    'side_setting_type' => $product->side_setting_type,
                    'min_size' => $sizes[$product->min_size_id] ?? null,
                    'max_size' => $sizes[$product->max_size_id] ?? null,
                    'metal' => $metals->get($product->metal_id),
                    'offers' => $product->findOffers(),
                    'average_ss_colour' => $product->average_ss_colour,
                    'average_ss_clarity' => $product->average_ss_clarity,
                    'approx_stones' => $product->approx_stones,
                    'gender' => $product->gender,
                ],
                'in_favorites'  => $favorites->inFavorites($product->id, Product::class),
                'in_compares'   => $favorites->inCompares($product->id, Product::class),
                'product_type'  => 'products',
                'selected_size' => $product->size_slug ? $sizeCollection->firstWhere('slug', '=', $product->size_slug) : null,
                'is_sold_out' => $product->is_sold_out,
                'in_store' => $product->in_store,
                'sold_out_title' => $product->getSoldOutTitle(),
                'is_available' => $product->isAvailable(),
            ];
        }

        return $response;
    }
}