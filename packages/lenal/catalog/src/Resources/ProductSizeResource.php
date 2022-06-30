<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RingStyleResource
 *
 * @mixin \lenal\catalog\Models\Products\ProductSize
 */
class ProductSizeResource extends JsonResource
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
