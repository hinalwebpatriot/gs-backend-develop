<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FeedSliderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sliderCollection = BlocksResource::collection($this->collection);
        $slider = [];
        foreach ($sliderCollection->sortBy('link') as $slideData) {
            $images = [];
            foreach ($slideData->media as $media) {
                $images[] = $media->getFullUrl();
            }
            $slider[] = $images;
        }
        return ['data' => $slider];
    }
}