<?php

namespace lenal\banners\Facades;

use Illuminate\Support\Facades\Facade;

class Banners extends Facade {

    public static function getFacadeAccessor()
    {
        return 'banners';
    }
}
