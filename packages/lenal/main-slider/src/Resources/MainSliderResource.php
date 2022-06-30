<?php

namespace lenal\MainSlider\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use lenal\MainSlider\Models\MainSlider;
use lenal\MainSlider\Models\MainSliderSlide;
use lenal\MainSlider\Models\MainSliderVideo;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class MainSliderResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_slider' => !$this->hasVideo(),
            'is_video'  => $this->hasVideo(),
            'slides'    => $this->getSlides(),
            'video'     => $this->getVideo(),
        ];
    }

    /**
     * @return bool
     */
    private function hasVideo()
    {
        return $this->video instanceof MainSliderVideo;
    }

    /**
     * @return array|mixed
     */
    private function getSlides()
    {
        return $this->hasVideo()
            ? []
            : $this->slides->map(function (MainSliderSlide $slide) {
                return new MainSliderSlideResource($slide);
            });
    }

    /**
     * @return array|mixed
     */
    private function getVideo()
    {
        return $this->hasVideo()
            ? new MainSliderVideoResource($this->video)
            : null;
    }
}