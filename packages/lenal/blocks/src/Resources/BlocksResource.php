<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlocksResource extends JsonResource
{
    private $returnFields;

    public function __construct($resource, $returnFields = [])
    {
        $this->returnFields = $returnFields; // use this param to return only specified fields
        parent::__construct($resource);
    }
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = parent::toArray($request);

        // get full urls for single files
        if (isset($result['image']) && $result['image']) {
            $result['image'] = asset(Storage::url($result['image']));
        }
        if (isset($result['file']) && $result['file']) {
            $result['file'] = asset(Storage::url($result['file']));
        }
        // get full urls for multiple files from media
        $blockMedia = [];
        foreach ($this->media as $media) {
            $lang = $media->getCustomProperty('language')? : config('translatable.fallback_locale');
            $blockMedia[$media->collection_name][$lang] = $media->getFullUrl();
        }

        return (is_array($this->returnFields) && !empty($this->returnFields))
            ? array_only(array_merge($result, $blockMedia), $this->returnFields)
            : array_merge($result, $blockMedia);
    }

    protected function getLocalizedMedia($resource, $mediaKey)
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('translatable.fallback_locale');
        if (isset($resource[$mediaKey][$locale])) {
            return $resource[$mediaKey][$locale];
        }

        return isset($resource[$mediaKey][$fallbackLocale])? $resource[$mediaKey][$fallbackLocale]: null;
    }
}