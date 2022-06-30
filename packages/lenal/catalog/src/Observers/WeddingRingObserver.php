<?php

namespace lenal\catalog\Observers;

use Illuminate\Support\Str;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Services\ProductCustomFieldService;

/**
 * Class WeddingRingObserver
 *
 * @package lenal\catalog\Observers
 */
class WeddingRingObserver
{
    /**
     * @param WeddingRing $wedding_ring
     */
    public function saving(WeddingRing $wedding_ring)
    {
        if (!$wedding_ring->group_sku) {
            $wedding_ring->group_sku = $wedding_ring->item_name;
        }

        if (is_null($wedding_ring->delivery_period)) {
            $wedding_ring->delivery_period = 0;
        }

        $wedding_ring->group_sku = Str::kebab($wedding_ring->group_sku);
        $wedding_ring->calcDiscount(false);
    }

    public function saved(WeddingRing $ring)
    {
        $ring->flushImageCache();
        (new ProductCustomFieldService($ring))->sync();
    }

    /**
     * @param WeddingRing $wedding_ring
     */
    public function deleting(WeddingRing $wedding_ring)
    {
        $wedding_ring->staticBlocks()->detach();
        $wedding_ring->customFields()->delete();
        $wedding_ring->unsearchable();
    }
}
