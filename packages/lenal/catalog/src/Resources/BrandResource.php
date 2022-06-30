<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BrandResource
 * @mixin \lenal\catalog\Models\Products\Brand
 */
class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $brandStory = null;

        if ($this->story_title || $this->story_video || $this->story_text) {
            $brandStory = [
                'title' => $this->story_title,
                'video' => $this->story_video,
                'text' => $this->story_text,
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'story' => $brandStory
        ];
    }
}
