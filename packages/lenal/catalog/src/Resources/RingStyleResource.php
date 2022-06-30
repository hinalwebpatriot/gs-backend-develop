<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class RingStyleResource
 *
 * @package lenal\catalog\Resources
 */
class RingStyleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->getMedia('image')->first();
        $image_hover = $this->getMedia('image-hover')->first();

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'image'       => $image instanceof Media
                ? $image->getFullUrl()
                : null,
            'image_hover' => $image instanceof Media
                ? $image_hover->getFullUrl()
                : null,
            'gender'      => $this->gender,
        ];
    }
}
