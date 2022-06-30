<?php

namespace lenal\static_pages\Facades;

use Illuminate\Support\Facades\Facade;

class StaticPage extends Facade {

    public static function getFacadeAccessor()
    {
        return 'static_pages';
    }
}
