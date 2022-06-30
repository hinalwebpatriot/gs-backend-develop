<?php

namespace lenal\auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('custom_auth', 'lenal\auth\Helpers\Auth');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/auth.php') => config_path('auth.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/auth/') => base_path('packages/lenal/auth')
        ]);
    }
}
