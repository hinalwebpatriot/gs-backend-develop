<?php

namespace lenal\MainSlider\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class MainSliderSlideResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'image' => Storage::disk(config('filesystems.cloud'))->url($this->image),
            'undercover' => $this->undercover
                ? Storage::disk(config('filesystems.cloud'))->url($this->undercover)
                : '',
            'undercover_video' => $this->undercover_video
                ? Storage::disk(config('filesystems.cloud'))->url($this->undercover_video)
                : '',
            'youtube_code' => $this->youtube_code,
            'bg_color' => $this->bg_color,
            'alt' => $this->alt,
            'slider_text' => $this->slider_text,
            'browse_button_title' => $this->browse_button_title,
            'browse_button_link' => $this->browse_button_link,
            'detail_button_title' => $this->detail_button_title,
            'detail_button_link' => $this->detail_button_link,
        ];
    }
}