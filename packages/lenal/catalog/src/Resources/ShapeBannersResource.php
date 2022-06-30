<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ShapeBannersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $banners = [];
        if (!$this->getMedia('banner')->isEmpty()) {
            // banners = [en => full_url, ru => full_url, ...]
            foreach ($this->getMedia('banner') as $media) {
                $lang = $media->getCustomProperty('language')? : config('translatable.fallback_locale');
                $banners[$lang] = $media->getFullUrl();
            };
        }
        $data = parent::toArray($request);
        $locale = app()->getLocale();
        $fallbackLocale = config('translatable.fallback_locale');
        return [
            'id' => $data['id'],
            'title' => $this->title,
            'slug' => $this->slug,
            'alt' => $this->alt,
            'preview_image'=> Storage::disk(config('filesystems.cloud'))->url($this->preview_image),
            'banner' => isset($banners[$locale])
                ? $banners[$locale]
                : isset($banners[$fallbackLocale])? $banners[$fallbackLocale]: null

        ];
    }
}
