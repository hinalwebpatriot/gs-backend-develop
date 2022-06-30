<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlocksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['data' => BlocksResource::collection($this->collection)];
    }
}