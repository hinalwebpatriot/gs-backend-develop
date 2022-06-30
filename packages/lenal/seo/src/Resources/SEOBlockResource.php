<?php

namespace lenal\seo\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SEOBlockResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $result = [
            'title' => $this->title,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'image' => $this->image? asset(Storage::disk(config('filesystems.cloud'))->url($this->image)): null,
            'collapses' => CollapseResource::collection($this->collapses)
        ];

        if ($this->page == 'diamonds-feed-expert') {
            $result += [
                'expert_name' => $this->expert_name,
                'brand' => $this->brand,
            ] ;
        }

        return $result;
    }
}