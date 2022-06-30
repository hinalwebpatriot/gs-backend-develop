<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DiamondsHelper
 *
 * @package lenal\catalog\Facades
 * @method static getDiamondMargin(\lenal\catalog\Models\Diamonds\Diamond $diamond): ?float
 * @method static createDiamond(\Illuminate\Http\Request $request): \lenal\catalog\Models\Diamonds\Diamond
 * @method static updateDiamond(string $sku, \Illuminate\Http\Request $request): \lenal\catalog\Models\Diamonds\Diamond
 * @mixin \lenal\catalog\Helpers\DiamondsHelper
 */
class DiamondsHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'diamonds_helper';
    }
}
