<?php

namespace lenal\catalog\Repositories;


use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Models\Products\ProductStyle;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Resources\CategoryResource;
use lenal\catalog\Resources\MetalResource;
use lenal\catalog\Resources\ProductSizeResource;
use lenal\catalog\Resources\ProductStyleResource;
use lenal\offers\Models\Offer;
use lenal\offers\Resources\OffersResource;

class ProductFilterRepository
{
    public function categoryFilters(Category $category, $categories)
    {
        return [
            'metal' => MetalResource::collection($this->getMetals($category->id)),
            'price' => [
                'min' => Product::query()->scopes(['withMinCalculatedPrice'])->first()->min_calculated_price,
                'max' => Product::query()->scopes(['withMaxCalculatedPrice'])->first()->max_calculated_price,
            ],
            'style' => [],//ProductStyleResource::collection(ProductStyle::all()),
            'size' => ProductSizeResource::collection(ProductSize::all()),
            'brands' => Brand::all()->map(function(Brand $brand) {
                return $brand->map();
            }),
            'offers' => OffersResource::collection(Offer::query()->scopes(['withActiveOrder'])->get()),
            'categories' => CategoryResource::collection($categories)
        ];
    }

    public function getMetals($categoryId)
    {
        return Metal::query()
            ->select('metals.*')
            ->join('products AS p', 'p.metal_id', 'metals.id')
            ->where('p.category_id', $categoryId)
            ->groupBy('metals.id')
            ->get();
    }
}