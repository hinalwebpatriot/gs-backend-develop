<?php

namespace lenal\subscribe\Facades;

use Illuminate\Support\Facades\Facade;

class Subscribe extends Facade {

    public static function getFacadeAccessor()
    {
        return 'subscribe';
    }
}
