<?php

namespace lenal\auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CustomAuth
 * @mixin \lenal\auth\Helpers\Auth
 */
class CustomAuth extends Facade {

    public static function getFacadeAccessor()
    {
        return 'custom_auth';
    }
}
