<?php

namespace lenal\seo\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class SEOMetaResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'h1' => $this->h1,
            'seo_text' => $this->seo_text,
        ];
    }
}