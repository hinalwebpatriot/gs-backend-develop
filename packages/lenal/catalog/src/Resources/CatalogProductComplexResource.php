<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Collections\ProductFeedCollection;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Repositories\ProductRepository;

/**
 * @mixin \lenal\catalog\Models\Products\Product
 */
class CatalogProductComplexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productsRepository = new ProductRepository();

        $products = $productsRepository->fetchByGroupSku($this->group_sku);
        $productSizes = [];
        if ($this->minSize && $this->maxSize) {
            $productSizes = ProductSizeResource::collection($productsRepository->fetchProductSizes($this->minSize->slug,
                $this->maxSize->slug));
        }

        $similarProducts = $products->filter(function (Product $product) {
            return $product->id != $this->id;
        });

        return [
            'metal_list' => MetalResource::collection(
                $products->map(function (Product $product) {
                    return $product->metal;
                })->unique()
            ),
            'size_list' => $productSizes,
            'shape_list' => [],
            'selected' => new CatalogProductResource($this->resource),
            'similar' => (new ProductFeedCollection($similarProducts))->resolve(),
        ];
    }
}
