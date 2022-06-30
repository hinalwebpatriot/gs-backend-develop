<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DiamondsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => DiamondResource::collection($this->collection)
        ];
    }
}
