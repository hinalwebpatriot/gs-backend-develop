<?php

namespace lenal\subscribe;

use Illuminate\Support\ServiceProvider;

class SubscribeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('subscribe', 'lenal\subscribe\Helpers\SubscribeHelper');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/subscribe.php') => config_path('subscribe.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/subscribe/') => base_path('packages/lenal/subscribe')
        ]);
    }
}
