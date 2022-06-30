<?php

namespace lenal\landings;

use Illuminate\Support\ServiceProvider;
use lenal\landings\Models\Landing;
use lenal\landings\Observers\LandingObserver;

class LandingsServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Landing::observe(LandingObserver::class);

        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
