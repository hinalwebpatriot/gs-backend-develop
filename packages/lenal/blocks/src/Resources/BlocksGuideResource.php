<?php

namespace lenal\blocks\Resources;

class BlocksGuideResource extends BlocksResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->title,
            'file' => $this->getLocalizedMedia(parent::toArray($request), 'guide-pdf'),
            'video_link' => $this->video_link
        ];
    }
}