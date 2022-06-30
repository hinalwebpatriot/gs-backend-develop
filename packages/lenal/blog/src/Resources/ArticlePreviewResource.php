<?php

namespace lenal\blog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticlePreviewResource extends JsonResource {

    public function toArray($request)
    {
        $image = $this->getLocalizedMedia('main_image');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'preview_text' => $this->preview_text,
            'slug' => $this->slug,
            'view_count' => $this->view_count,
            'date' => $this->created_at->timestamp,
            'category' => new BlogCategoryResource($this->category),
            'image' => $image
        ];
    }

    protected function getLocalizedMedia($mediaKey)
    {
        $blockMedia = [];
        foreach ($this->media as $media) {
            $lang = $media->getCustomProperty('language')? : config('translatable.fallback_locale');
            $blockMedia[$media->collection_name][$lang] = $media->getFullUrl();
        }
        $locale = app()->getLocale();
        $fallbackLocale = config('translatable.fallback_locale');
        return isset($blockMedia[$mediaKey][$locale])
            ? $blockMedia[$mediaKey][$locale]
            : isset($blockMedia[$mediaKey][$fallbackLocale])? $blockMedia[$mediaKey][$fallbackLocale]: null;
    }
}