<?php

namespace lenal\promo_registration\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin \lenal\promo_registration\Models\PromoRegistrationText
 */
class ContentResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Media $photo */
        $photo = $this->getMedia('promo_register')->first();

        return [
            'header' => $this->header,
            'preview' => $this->preview,
            'photo' => $photo ? $photo->getFullUrl() : asset('files/no_image_placeholder.png'),
        ];
    }
}