<?php

namespace lenal\blog\Facades;

use Illuminate\Support\Facades\Facade;

class Blog extends Facade {

    public static function getFacadeAccessor()
    {
        return 'blog';
    }
}
