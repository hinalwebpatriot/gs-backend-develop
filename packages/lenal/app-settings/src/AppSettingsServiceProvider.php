<?php

namespace lenal\AppSettings;

use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('app_settings', 'lenal\AppSettings\Helpers\AppSettingsHelper');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
