<?php

namespace lenal\blocks\Resources;

use \Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Resources\EngagementRingResource;
use lenal\catalog\Models\Rings\EngagementRing;

class BlockOccasionResource extends JsonResource {

    public function toArray($request)
    {
        foreach ($this->blockEngagementRings as $engagement) {
            $engagements_id[] = $engagement->engagement_ring_id;
        }

        return [
            'title' => $this->title,
            'products' => EngagementRingResource::collection(EngagementRing::withCalculatedPrice()
                ->whereIn("id", $engagements_id)->get())
        ];
    }
}