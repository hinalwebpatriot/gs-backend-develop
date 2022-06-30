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

class ProductCategoryRepository
{
    /**
     * Return categories
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|[]
     */
    public function getList()
    {
        return Category::query()->get();
    }

    public function getBySlug($slug)
    {
        return Category::query()->where('slug', $slug)->first();
    }
}