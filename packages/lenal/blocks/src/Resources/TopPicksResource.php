<?php

namespace lenal\blocks\Resources;

use \Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Resources\ProductResource;

class TopPicksResource extends JsonResource {
    public function toArray($request)
    {
        $items = collect();

        // reload items from db to get calculated price
        if (!$this->blockEngagementRings->isEmpty()) {
            $items = $items->merge(
                EngagementRing::withCalculatedPrice()
                    ->whereIn('id', $this->blockEngagementRings->map(function ($item) { return $item->id; }))->get()
            );
        }
        if (!$this->blockWeddingRings->isEmpty()) {
            $items = $items->merge(
                WeddingRing::withCalculatedPrice()
                    ->whereIn('id', $this->blockWeddingRings->map(function ($item) { return $item->id; }))->get()
            );
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'products' => ProductResource::collection($items)
        ];
    }
}