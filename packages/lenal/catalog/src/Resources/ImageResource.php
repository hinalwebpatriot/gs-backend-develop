<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class ImageResource
 * @mixin Media
 */
class ImageResource extends JsonResource
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
            'name'      => $this->name,
            'mime_type' => $this->mime_type,
            'size'      => $this->size,
            'path'      => [
                'origin' => $this->getFullUrl(),
                'thumb'  => $this->getFullUrl('thumb'),
                'medium' => $this->getFullUrl('medium-size'),
                'feed' => $this->getFullUrl('feed'),
                'feed_min' => $this->hasGeneratedConversion('feed_min') ? $this->getFullUrl('feed_min') : $this->getFullUrl('feed'),
            ]
        ];
    }
}

