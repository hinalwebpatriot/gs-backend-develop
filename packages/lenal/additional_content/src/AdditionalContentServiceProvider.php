<?php

namespace lenal\additional_content;

use Illuminate\Support\ServiceProvider;

class AdditionalContentServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('additional_content', 'lenal\additional_content\Helpers\AdditionalContent');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/additional_content.php')
            => config_path('additional_content.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/additional_content/') => base_path('packages/lenal/additional_content')
        ]);
    }
}
