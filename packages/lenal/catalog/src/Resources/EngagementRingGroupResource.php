<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\EngagementsHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\RingSize;

/**
 * Class EngagementRingGroupResource
 *
 * @package lenal\catalog\Resources
 */
class EngagementRingGroupResource extends JsonResource
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
        $engagement_list = EngagementRing::withCalculatedPrice()
            ->withResourceRelation()
            ->where('group_sku', $this->group_sku)
            ->get();

        $ring_sizes = RingSize::orderBy('slug')
            ->whereBetween('slug', [
                $this->minRingSize->slug,
                $this->maxRingSize->slug
            ])
            ->get();

        $shapesSKU = EngagementsHelper::getRingShapesSKU($this);

        return [
            'metal_list' => MetalResource::collection(
                $engagement_list->map(function ($engagement) {
                    return $engagement->metal;
                })->unique()
            ),
            'size_list'  => RingSizeResource::collection($ring_sizes),
            'shape_list' => ProductShapeResource::collection($shapesSKU),
            'selected'   => new EngagementRingResource($this->resource),
            'similar'    => EngagementRingResource::collection(
                $engagement_list->filter(function ($engagement) {
                    return $engagement->id != $this->id;
                })
            ),
        ];
    }
}
