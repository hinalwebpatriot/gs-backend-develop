<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use lenal\offers\Models\Offer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class OfferResource
 *
 * @package lenal\catalog\Resources
 * @mixin Offer
 */
class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'slug'   => $this->slug,
        ];
    }
}
