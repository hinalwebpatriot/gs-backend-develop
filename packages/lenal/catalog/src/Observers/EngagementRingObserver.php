<?php

namespace lenal\catalog\Observers;

use Illuminate\Support\Str;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Services\ProductCustomFieldService;

/**
 * Class EngagementRingObserver
 *
 * @package lenal\catalog\Observers
 */
class EngagementRingObserver
{
    /**
     * @param EngagementRing $engagement_ring
     */
    public function saving(EngagementRing $engagement_ring)
    {
        $engagement_ring->group_sku = Str::kebab($engagement_ring->group_sku);

        if (!$engagement_ring->group_sku) {
            $engagement_ring->group_sku = collect([
                Str::kebab($engagement_ring->item_name),
                Str::kebab($engagement_ring->stoneShape->slug),
                number_format($engagement_ring->stone_size, 2),
                Str::kebab($engagement_ring->setting_type),
            ])->implode('/');
        }

        if (is_null($engagement_ring->delivery_period)) {
            $engagement_ring->delivery_period = 0;
        }

        $engagement_ring->calcDiscount(false);
    }

    /**
     * @param EngagementRing $engagement_ring
     */
    public function deleting(EngagementRing $engagement_ring)
    {
        $engagement_ring->staticBlocks()->detach();
        $engagement_ring->unsearchable();
        $engagement_ring->customFields()->delete();
    }

    public function saved(EngagementRing $ring)
    {
        $ring->flushImageCache();
        (new ProductCustomFieldService($ring))->sync();
    }
}
