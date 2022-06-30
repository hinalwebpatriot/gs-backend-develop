<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockSimpleTextResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text
        ];
    }
}