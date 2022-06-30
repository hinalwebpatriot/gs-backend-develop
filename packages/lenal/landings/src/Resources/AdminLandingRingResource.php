<?php

namespace lenal\landings\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminLandingRingResource
 * @mixin \lenal\catalog\Models\Rings\EngagementRing
 */
class AdminLandingRingResource extends JsonResource
{
    public function toArray($request)
    {
        $images = $this->getMedia('engagement-images');

        return [
            'id' => $this->id,
            'img_url' => $images ? $images->first()->getFullUrl('medium-size') : asset('files/no_image_placeholder.png'),
            'name' => $this->title . ' (' . $this->sku . ')'
        ];
    }
}