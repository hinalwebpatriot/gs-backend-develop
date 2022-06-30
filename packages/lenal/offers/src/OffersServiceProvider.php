<?php

namespace lenal\offers;

use Illuminate\Support\ServiceProvider;
use lenal\PriceCalculate\Commands\CurrencyRateApiAutoUpdate;
use lenal\PriceCalculate\Helpers\CountryVat;
use lenal\PriceCalculate\Helpers\CurrencyRate;

/**
 * Class PriceCalculateServiceProvider
 *
 * @package lenal\PriceCalculate
 */
class OffersServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
