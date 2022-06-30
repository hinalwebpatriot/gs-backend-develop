<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\catalog\Collections\EngagementRingFeedCollection;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Helpers\FilterTypes\FilterHavingWithRange;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Repositories\RingStyleRepository;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class EngagementRingGroupCollection
 *
 * @package lenal\catalog\Resources
 */
class EngagementRingGroupCollection extends ResourceCollection
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
        $engagement_list_ids = $this->collection
            ->reduce(function ($engagement_ids, $engagement_group) {
                return $engagement_ids->concat(explode(',', $engagement_group->ids));
            }, collect());
        $engagement_builder = EngagementRing::withCalculatedPrice()
            ->withResourceRelation()
            ->whereIn('id', $engagement_list_ids);

        FilterBuilderHelper::applyOrder([
            SortWithProperty::make($engagement_builder, true, 'is_top', 'desc'),
            SortWithProperty::make(
                $engagement_builder,
                $request->input('sort.field') == 'price',
                'calculated_price',
                $request->input('sort.order')
            ),
        ]);

        $engagement_list = $engagement_builder->with(['offers', 'minRingSize', 'maxRingSize', 'media'])->get();
        $data = $this->collection
            ->map(function ($engagement_group) use ($engagement_list) {
                $group_ids = explode(',', $engagement_group->ids);
                $engagements = $engagement_list
                    ->filter(function ($engagement) use ($group_ids) {
                        return in_array($engagement->id, $group_ids);
                    });

                if ($engagements->count()) {
                    return [
                        'group_sku' => $engagement_group->group_sku,
                        'metals' => $engagements
                            ->mapWithKeys(function ($engagement) {
                                return [
                                    $engagement->metal->slug => new MetalResource($engagement->metal),
                                ];
                            }),
                        'rings' => (new EngagementRingFeedCollection($engagements))->toArray(),
                    ];
                }
            })->filter(function($engagement_group) {
                return $engagement_group != null;
            })->toArray();

        return [
            'data' => $data,
        ];
    }
}
