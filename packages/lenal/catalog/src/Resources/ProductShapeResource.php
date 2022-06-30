<?php

namespace lenal\catalog\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductShapeResource extends JsonResource
{
    public function toArray($request)
    {
        $shapeResource = (new ShapeResource($this->stoneShape))->toArray($request);
        $ringResource = [
            'ring_id' => $this->id,
            'ring_slug' => $this->slug,
            'h1'       =>$this->h1,
            'h2'       =>$this->h2
        ];
        return array_merge($shapeResource, $ringResource);
    }
}