<?php

namespace lenal\additional_content\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Models\Rings\WeddingRingStyle;
use lenal\catalog\Resources\MetalResource;
use lenal\catalog\Resources\RingStyleResource;

class MenuDropdownCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        foreach ($this->collection as $menu_item) {
            $data[$menu_item->menu_item] = [
                'images' => [],
            ];

            foreach ($menu_item->media as $media) {
                $data[$menu_item->menu_item]['images'][] = [
                    'image_url' => $media->getFullUrl(),
                ];
            }

            if ($menu_item->menu_item == 'engagement-rings') {
                unset($data[$menu_item->menu_item]['images']);
                $data[$menu_item->menu_item]['style'] = RingStyleResource::collection(EngagementRingStyle::all());
                $data[$menu_item->menu_item]['metal'] = MetalResource::collection(Metal::where('engagement_off', false)
                    ->get()
                );
            }

            if ($menu_item->menu_item == 'wedding-rings') {
                $data[$menu_item->menu_item]['style']['male'] = RingStyleResource::
                collection(WeddingRingStyle::where('gender', 'male')->get());
                $data[$menu_item->menu_item]['style']['female'] = RingStyleResource::
                collection(WeddingRingStyle::where('gender', 'female')->get());
                $data[$menu_item->menu_item]['metal'] = MetalResource::collection(Metal::all());
            }
        }

        return $data;
    }
}
