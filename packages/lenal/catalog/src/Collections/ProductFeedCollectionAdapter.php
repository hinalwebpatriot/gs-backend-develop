<?php

namespace lenal\catalog\Collections;


use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\ResourceResponse;
use JsonSerializable;

class ProductFeedCollectionAdapter implements JsonSerializable
{
    protected $product;

    public static function collection($products)
    {
        return new ProductFeedCollection($products);
    }

    /**
     * ProductFeedCollectionAdapter constructor.
     * @param \lenal\catalog\Models\Products\Product $product
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    public function resolve($request = null)
    {
        return $this->toArray($request = $request ?: request());
    }

    public function toArray($request)
    {
        $products = (new ProductFeedCollection(collect([$this->product])))->resolve();

        return $products[0] ?? [];
    }

    public function jsonSerialize()
    {
        return $this->resolve(request());
    }
}