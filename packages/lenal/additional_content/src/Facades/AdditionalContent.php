<?php

namespace lenal\additional_content\Facades;

use Illuminate\Support\Facades\Facade;

class AdditionalContent extends Facade {

    public static function getFacadeAccessor()
    {
        return 'additional_content';
    }
}
