<?php

namespace lenal\promo_registration;

use Illuminate\Support\ServiceProvider;
use lenal\landings\Models\Landing;
use lenal\landings\Observers\LandingObserver;

class PromoRegistrationProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->loadViewsFrom(realpath(dirname(__FILE__)) . '/views', 'promo_registration');
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
