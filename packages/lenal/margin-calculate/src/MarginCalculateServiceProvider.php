<?php

namespace lenal\MarginCalculate;

use Illuminate\Support\ServiceProvider;
use lenal\MarginCalculate\Helpers\MarginCalculate;

class MarginCalculateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('margin_calculate', MarginCalculate::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
