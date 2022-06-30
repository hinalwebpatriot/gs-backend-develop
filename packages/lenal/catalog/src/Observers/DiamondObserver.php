<?php

namespace lenal\catalog\Observers;

use Carbon\Carbon;
use lenal\catalog\Facades\DiamondsHelper;
use lenal\catalog\Models\Diamonds\Diamond;

/**
 * Class DiamondObserver
 *
 * @package lenal\catalog\Observers
 */
class DiamondObserver
{
    /**
     * @param Diamond $diamond
     */
    public function saving(Diamond $diamond)
    {
        $price = $diamond->raw_price ?? 0;
        $diamond->margin_price = 0;
        if (!$diamond->manufacturer->custom_made) {
            $margin = DiamondsHelper::getDiamondMargin($diamond);
            $diamond->margin_price = $price * $margin / 100;
        }
        $diamond->createSlug();
        $diamond->createSku();

        $diamond->videoNormalize();
    }

    /**
     * @param Diamond $diamond
     */
    public function deleting(Diamond $diamond)
    {
        $diamond->unsearchable();
    }

    public function creating(Diamond $diamond)
    {
        if (!$diamond->updated_at) {
            $diamond->updated_at = Carbon::now();
        }
    }
}
