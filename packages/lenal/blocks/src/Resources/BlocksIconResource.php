<?php

namespace lenal\blocks\Resources;

class BlocksIconResource extends BlocksResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'items' => MediaIconResource::collection($this->media),
        ];
    }
}