<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 * @mixin \lenal\catalog\Models\Products\Category
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name
        ];
    }
}
