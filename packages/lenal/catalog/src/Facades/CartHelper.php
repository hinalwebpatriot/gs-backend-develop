<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CartHelper
 * @mixin \lenal\catalog\Helpers\CartHelper
 */
class CartHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'catalog_cart_helper';
    }
}
