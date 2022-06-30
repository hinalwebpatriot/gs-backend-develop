<?php

namespace lenal\blocks\Resources;

use \Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Resources\EngagementRingResource;

class BlockRingsSliderResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'products' => EngagementRingResource::collection($this->blockEngagementRings)
        ];
    }
}