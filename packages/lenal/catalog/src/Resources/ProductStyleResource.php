<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class ProductStyleResource
 * @mixin \lenal\catalog\Models\Products\ProductStyle
 */
class ProductStyleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Media $image */
        $image = $this->getMedia('image')->first();

        /** @var Media $imageHover */
        $imageHover = $this->getMedia('image-hover')->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $image ? $image->getFullUrl() : null,
            'image_hover' => $imageHover ? $imageHover->getFullUrl() : null,
        ];
    }
}
