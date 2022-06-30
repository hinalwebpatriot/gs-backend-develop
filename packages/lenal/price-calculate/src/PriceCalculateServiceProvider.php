<?php

namespace lenal\PriceCalculate;

use Illuminate\Support\ServiceProvider;
use lenal\PriceCalculate\Commands\CurrencyRateApiAutoUpdate;
use lenal\PriceCalculate\Helpers\CountryVat;
use lenal\PriceCalculate\Helpers\CurrencyRate;

/**
 * Class PriceCalculateServiceProvider
 *
 * @package lenal\PriceCalculate
 */
class PriceCalculateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('currency-rate', CurrencyRate::class);
        $this->app->bind('country-vat', CountryVat::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            __DIR__.'/config/price_calculate.php' => config_path('price_calculate.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CurrencyRateApiAutoUpdate::class,
            ]);
        }
    }
}
