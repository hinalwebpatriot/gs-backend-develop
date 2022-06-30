<?php

namespace lenal\blocks\Resources;

use Illuminate\Support\Facades\Storage;

class BlocksPromoResource extends BlocksResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'banner' => $this->getLocalizedMedia(parent::toArray($request), 'promo'),
            'undercover_video' => Storage::disk(config('filesystems.cloud'))->url($this->undercover_video),
            'link' => $this->link,
        ];
    }
}