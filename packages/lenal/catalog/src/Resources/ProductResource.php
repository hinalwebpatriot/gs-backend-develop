<?php
namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Collections\ProductFeedCollectionAdapter;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class ProductResource extends JsonResource {

    public function toArray($request)
    {
        switch (get_class($this->resource)) {
            case Diamond::class: return (new DiamondResource($this->resource))->toArray($request);
            case WeddingRing::class: return (new WeddingRingResource($this->resource))->toArray($request);
            case EngagementRing::class: return (new EngagementRingResource($this->resource))->toArray($request);
            case Product::class: return (new ProductFeedCollectionAdapter($this->resource))->toArray($request);
            default: return parent::toArray($request);
        }
    }
}