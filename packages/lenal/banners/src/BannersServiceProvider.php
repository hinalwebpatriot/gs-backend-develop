<?php

namespace lenal\banners;

use Illuminate\Support\ServiceProvider;

class BannersServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('banners', 'lenal\banners\Helpers\Banners');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/banners.php') => config_path('banners.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/banners/') => base_path('packages/lenal/banners')
        ]);
    }
}
