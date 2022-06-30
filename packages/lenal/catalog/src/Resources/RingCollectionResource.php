<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RingStyleResource
 *
 * @package lenal\catalog\Resources
 */
class RingCollectionResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'story' => ($this->story_title || $this->story_video || $this->story_text)
                ? [
                    'title' => $this->story_title,
                    'video' => $this->story_video,
                    'text' => $this->story_text,
                ]
                : null
        ];
    }
}
