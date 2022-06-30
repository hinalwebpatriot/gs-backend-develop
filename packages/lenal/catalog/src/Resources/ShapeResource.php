<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class ShapeResource
 *
 * @package lenal\catalog\Resources
 */
class ShapeResource extends JsonResource
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
            'id'     => $this->id,
            'title'  => $this->title,
            'slug'   => $this->slug,
            'image'  => $this->preview_image ? Storage::disk(config('filesystems.cloud'))->url($this->preview_image) : '#',
        ];
    }
}
