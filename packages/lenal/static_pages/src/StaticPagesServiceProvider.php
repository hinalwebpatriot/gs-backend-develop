<?php

namespace lenal\static_pages;

use Illuminate\Support\ServiceProvider;

class StaticPagesServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('static_pages', 'lenal\static_pages\Helpers\StaticPages');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/static_pages.php') => config_path('static_pages.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/static_pages/') => base_path('packages/lenal/static_pages')
        ]);
    }
}
