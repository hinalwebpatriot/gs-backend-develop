<?php

namespace lenal\catalog\Resources\EngagementRings;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use lenal\catalog\Collections\EngagementRingFeedCollection;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Helpers\FilterTypes\FilterHavingWithRange;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Repositories\RingStyleRepository;
use lenal\catalog\Resources\MetalResource;
use lenal\catalog\Resources\MetalWithoutImageResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class EngagementRingGroupCollection
 *
 * @package lenal\catalog\Resources
 */
class FeedCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->collapse()->map(function ($group, $groupName) {
                $metals = collect([$group['mainItem']->metal_id])
                    ->merge(collect($group['extraItems'])
                        ->pluck('metal_id')->values()->toArray()
                    );
                $dataMetals = $this->metals($metals->values()->toArray());
                return [
                    'group_sku'  => $groupName,
                    'metals'     => array_values($dataMetals),
                    'item'       => new FeedItemResource($group['mainItem']),
                    'extraItems' => $group['extraItems']
                ];
            })->values()
        ];
    }

    protected function metals(array $ids)
    {
        return Metal::getList($ids, false);
    }
}
