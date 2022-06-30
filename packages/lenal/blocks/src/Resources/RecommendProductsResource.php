<?php

namespace lenal\blocks\Resources;

use \Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Resources\DiamondResource;
use lenal\catalog\Resources\EngagementRingResource;
use lenal\catalog\Resources\WeddingRingResource;

class RecommendProductsResource extends JsonResource {
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
            'products' => [
                'diamonds' => DiamondResource::collection(
                    $this->blockDiamonds
                        ->each(function ($item) { $item->id = $item->diamond_id? : $item->id; })
                ),
                'engagement-rings' => EngagementRingResource::collection(
                    $this->blockEngagementRings
                        ->each(function ($item) { $item->id = $item->engagement_ring_id? : $item->id; })
                ),
                'wedding-rings' => WeddingRingResource::collection(
                    $this->blockWeddingRings
                        ->each(function ($item) { $item->id = $item->wedding_ring_id? : $item->id; })
                ),
            ]
        ];
    }
}