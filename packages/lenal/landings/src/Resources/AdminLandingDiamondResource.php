<?php

namespace lenal\landings\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminLandingRingResource
 * @mixin \lenal\catalog\Models\Rings\EngagementRing
 */
class AdminLandingDiamondResource extends JsonResource
{
    public function toArray($request)
    {
        $image = $this->getMedia('diamond-images')->first();

        return [
            'id' => $this->id,
            'img_url' => $image ? $image->getFullUrl('medium-size') : asset('files/no_image_placeholder.png'),
            'name' => $this->title . ' (' . $this->sku . ')'
        ];
    }
}