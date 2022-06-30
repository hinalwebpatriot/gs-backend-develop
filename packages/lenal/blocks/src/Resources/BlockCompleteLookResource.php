<?php
namespace lenal\blocks\Resources;

class BlockCompleteLookResource extends BlocksResource {

    public function toArray($request)
    {
        $image = $this->getLocalizedMedia(parent::toArray($request), 'complete_look');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $image,
            'video' => !$image? $this->video_link: null
        ];
    }
}