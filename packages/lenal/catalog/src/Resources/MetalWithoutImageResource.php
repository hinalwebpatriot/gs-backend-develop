<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use lenal\catalog\Models\Rings\Metal;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class MetalWithoutImageResource
 *
 * @package lenal\catalog\Resources
 * @mixin Metal
 */
class MetalWithoutImageResource extends JsonResource
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
            'id'     => $this['id'],
            'title'  => $this['title'],
            'slug'   => $this['slug'],
        ];
    }
}
