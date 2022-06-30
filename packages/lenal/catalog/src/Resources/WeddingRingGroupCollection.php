<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\catalog\Collections\WeddingRingFeedCollection;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Repositories\MetalRepository;
use lenal\catalog\Repositories\RingStyleRepository;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class WeddingRingGroupCollection
 *
 * @package lenal\catalog\Resources
 */
class WeddingRingGroupCollection extends ResourceCollection
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
        $wedding_list_ids = $this->collection
            ->reduce(function ($wedding_ids, $wedding_group) {
                return $wedding_ids->concat(explode(',', $wedding_group->ids));
            }, collect());
        $wedding_builder = WeddingRing::withCalculatedPrice()
            ->whereIn('id', $wedding_list_ids);

        FilterBuilderHelper::applyOrder([
            SortWithProperty::make($wedding_builder, true, 'is_top', 'desc'),
            SortWithProperty::make(
                $wedding_builder,
                $request->input('sort.field') == 'price',
                'calculated_price',
                $request->input('sort.order')
            ),
        ]);

        $wedding_list = $wedding_builder->with(['offers', 'minRingSize', 'maxRingSize', 'media'])->get();
        $data = $this->collection
            ->map(function ($wedding_group) use ($wedding_list) {
                $group_ids = explode(',', $wedding_group->ids);
                $weddings = $wedding_list
                    ->filter(function ($wedding) use ($group_ids) {
                        return in_array($wedding->id, $group_ids);
                    });
                if ($weddings->count()) {
                    return [
                        'group_sku' => $wedding_group->group_sku,
                        'metals' => $weddings
                            ->mapWithKeys(function ($wedding) {
                                return [
                                    $wedding->metal->slug => new MetalResource($wedding->metal),
                                ];
                            }),
                        'rings' => (new WeddingRingFeedCollection($weddings))->toArray(),
                    ];
                }
            })->filter(function($wedding_group) {
                return $wedding_group != null;
            })->toArray();

        return [
            'data' => $data,
        ];
    }
}
