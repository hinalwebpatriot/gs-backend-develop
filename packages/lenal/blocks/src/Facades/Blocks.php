<?php

namespace lenal\blocks\Facades;

use Illuminate\Support\Facades\Facade;

class Blocks extends Facade {

    public static function getFacadeAccessor()
    {
        return 'blocks';
    }
}
