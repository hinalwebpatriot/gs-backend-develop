<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 2/11/19
 * Time: 6:22 PM
 */

namespace lenal\blocks\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MediaIconResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image' => $this->getFullUrl(),
            'title' => $this->getLocalizedValue($this->getCustomProperty('title')),
            'link' => $this->getLocalizedValue($this->getCustomProperty('link')),
        ];
    }

    private function getLocalizedValue($value)
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('translatable.fallback_locale');
        return isset($value[$locale])
            ? $value[$locale]
            : isset($value[$fallbackLocale])? $value[$fallbackLocale]: null;
    }
}