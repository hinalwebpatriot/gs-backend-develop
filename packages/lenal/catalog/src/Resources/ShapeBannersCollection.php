<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShapeBannersCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => ShapeBannersResource::collection($this->collection)
        ];
    }
}
