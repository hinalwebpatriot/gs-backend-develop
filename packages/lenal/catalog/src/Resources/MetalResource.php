<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class MetalResource
 *
 * @package lenal\catalog\Resources
 */
class MetalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->getMedia('image')->first();

        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'slug'   => $this->slug,
            'image'  => $image instanceof Media
                ? $image->getFullUrl()
                : null,
        ];
    }
}
