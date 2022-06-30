<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \lenal\catalog\Helpers\OrderHelper
 */
class OrderHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'catalog_order_helper';
    }
}
