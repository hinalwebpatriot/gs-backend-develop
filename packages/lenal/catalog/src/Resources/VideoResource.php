<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'      => $this->file_name,
            'mime_type' => $this->mime_type,
            'size'      => $this->size,
            'src'       => $this->getFullUrl(),
        ];
    }
}

