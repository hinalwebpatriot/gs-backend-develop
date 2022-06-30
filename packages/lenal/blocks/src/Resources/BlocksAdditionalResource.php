<?php

namespace lenal\blocks\Resources;

class BlocksAdditionalResource extends BlocksResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'text' => $this->text,
            'button_text' => $this->link_text,
            'button_link' => $this->link,
            'video_link' => $this->video_link,
        ];
    }
}