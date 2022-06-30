<?php

namespace lenal\social\Facades;

use Illuminate\Support\Facades\Facade;

class Social extends Facade {

    public static function getFacadeAccessor()
    {
        return 'social';
    }
}
