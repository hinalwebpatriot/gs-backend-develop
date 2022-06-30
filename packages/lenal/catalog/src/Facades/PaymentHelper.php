<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \lenal\catalog\Helpers\PaymentHelper
 */
class PaymentHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'payment_helper';
    }
}
