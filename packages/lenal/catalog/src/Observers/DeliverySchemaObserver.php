<?php

namespace lenal\catalog\Observers;

use lenal\catalog\Jobs\DeliveryDiamondsCacheRefreshJob;
use lenal\catalog\Models\DeliverySchema;
use lenal\catalog\Models\Products\Category;

/**
 * Class DeliverySchemaObserver
 *
 * @package lenal\catalog\Observers
 */
class DeliverySchemaObserver
{
    public function saved(DeliverySchema $deliverySchema)
    {
        if ($deliverySchema->category_slug == Category::DIAMONDS) {
            DeliveryDiamondsCacheRefreshJob::dispatch();
        }
    }

    public function deleting(DeliverySchema $deliverySchema)
    {
        if ($deliverySchema->category_slug == Category::DIAMONDS) {
            DeliveryDiamondsCacheRefreshJob::dispatch();
        }
    }
}
