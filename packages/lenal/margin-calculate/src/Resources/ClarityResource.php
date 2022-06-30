<?php

namespace lenal\MarginCalculate\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class ClarityResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug'  => $this->slug,
            'sort'  => $this->value,
        ];
    }
}