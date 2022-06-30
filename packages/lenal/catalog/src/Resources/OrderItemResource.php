<?php
namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Collections\ProductFeedCollection;
use lenal\catalog\Collections\ProductFeedCollectionAdapter;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class OrderItemResource extends JsonResource {

    public function toArray($request)
    {
        if (!$this->resource) {
            return parent::toArray($request);
        }

        switch (get_class($this->resource)) {
            case Diamond::class:
                $data = (new DiamondResource($this->resource))->toArray($request);
                break;
            case WeddingRing::class:
                $data = (new WeddingRingResource($this->resource))->toArray($request);
                break;
            case EngagementRing::class:
                $data = (new EngagementRingResource($this->resource))->toArray($request);
                break;
            case Product::class:
                $data = (new ProductFeedCollectionAdapter($this->resource))->toArray($request);
                break;
            default: return parent::toArray($request);
        }
        // item price, fixed on order create
        $data['price'] = [
            'old_count' => null,
            'count'     => round($this->сalculated_price, 2),
            'currency'  => $this->currency,
        ];
        return $data;
    }
}