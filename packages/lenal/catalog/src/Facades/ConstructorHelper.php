<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @mixin \lenal\catalog\Helpers\ConstructorHelper
 */
class ConstructorHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'catalog_constructor_helper';
    }
}
