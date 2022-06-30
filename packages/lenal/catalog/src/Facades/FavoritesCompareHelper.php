<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FavoritesCompareHelper
 * @mixin \lenal\catalog\Helpers\FavoritesCompareHelper
 */
class FavoritesCompareHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'catalog_fav_compare_helper';
    }
}
