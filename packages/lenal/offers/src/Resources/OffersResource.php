<?php

namespace lenal\offers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OffersResource
 *
 * @package lenal\offers
 */
class OffersResource extends JsonResource
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
        ];
    }
}
