<?php

namespace lenal\MarginCalculate\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class MarginResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'manufacturer' => new ManufacturerResource($this->whenLoaded('manufacturer')),
            'color'        => new ColorResource($this->whenLoaded('color')),
            'clarity'      => new ClarityResource($this->whenLoaded('clarity')),
            'is_round'     => $this->is_round,
            'carat_min'    => $this->carat_min,
            'carat_max'    => $this->carat_max,
            'margin'       => $this->margin,
        ];
    }
}