<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RingStyleResource
 *
 * @package lenal\catalog\Resources
 */
class RingSizeResource extends JsonResource
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
            'title'  => json_decode($this->size, true),
            'slug'   => $this->slug,
        ];
    }
}
