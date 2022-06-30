<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CommonHelper
 * @mixin \lenal\catalog\Helpers\CommonHelper
 */
class CommonHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'catalog_common_helper';
    }
}
