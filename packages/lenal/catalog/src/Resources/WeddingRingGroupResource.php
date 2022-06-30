<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRing;

/**
 * Class WeddingRingGroupResource
 *
 * @package lenal\catalog\Resources
 */
class WeddingRingGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $wedding_list = WeddingRing::withCalculatedPrice()
            ->where('group_sku', $this->group_sku)
            ->where('id', '!=', $this->id)
            ->get();

        $ring_sizes = RingSize::orderBy('slug')
            ->whereBetween('slug', [
                $this->minRingSize->slug,
                $this->maxRingSize->slug
            ])
            ->get();

        return [
            'metal_list' => MetalResource::collection(
                $wedding_list->map(function ($wedding) {
                    return $wedding->metal;
                })->unique()
            ),
            'size_list'  => RingSizeResource::collection($ring_sizes),
            'selected' => new WeddingRingResource($this->resource),
            'similar'  => WeddingRingResource::collection($wedding_list),
        ];
    }
}
