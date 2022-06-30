<?php

namespace lenal\blocks;

use Illuminate\Support\ServiceProvider;

class BlocksServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('blocks', 'lenal\blocks\Helpers\Blocks');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/blocks.php') => config_path('blocks.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/blocks/') => base_path('packages/lenal/blocks')
        ]);
    }
}
