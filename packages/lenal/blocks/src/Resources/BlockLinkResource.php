<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockLinkResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link
        ];
    }
}