<?php

namespace lenal\social;

use Illuminate\Support\ServiceProvider;

class SocialServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('social', 'lenal\social\Helpers\SocialHelper');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/social.php') => config_path('social.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/social/') => base_path('packages/lenal/social')
        ]);
    }
}
