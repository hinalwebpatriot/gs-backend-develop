<?php

namespace lenal\static_pages\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StaticPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        return [
            'title' => $this->title_translatable,
            'code' => $this->code,
            'image' => $this->image? asset(Storage::disk(config('filesystems.cloud'))->url($this->image)): null,
            'text' => $this->text_translatable
        ];
    }
}
