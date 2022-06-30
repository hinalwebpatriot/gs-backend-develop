<?php

namespace lenal\catalog\Observers;

use lenal\catalog\Jobs\RecalculateMarginPricesJob;
use lenal\MarginCalculate\Models\MarginCalculate;

/**
 * Class MarginCalculateObserver
 *
 * @package lenal\catalog\Observers
 */
class MarginCalculateObserver
{
    /**
     * @param MarginCalculate $margin_calculate
     */
    public function saved(MarginCalculate $margin_calculate)
    {
        RecalculateMarginPricesJob::dispatch($margin_calculate);
    }
}
