<?php

namespace lenal\AppSettings\Facades;

use Illuminate\Support\Facades\Facade;

class AppSettings extends Facade {

    public static function getFacadeAccessor()
    {
        return 'app_settings';
    }
}
