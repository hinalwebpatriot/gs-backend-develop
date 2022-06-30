<?php

namespace lenal\MainSlider\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class MainSliderVideoResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'youtube_link' => $this->youtube_link,
        ];
    }
}