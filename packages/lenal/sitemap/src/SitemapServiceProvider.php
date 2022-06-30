<?php

namespace lenal\sitemap;

use Illuminate\Support\ServiceProvider;
use lenal\sitemap\Commands\SitemapGenerate;

class SitemapServiceProvider extends ServiceProvider
{

   /* public function register()
    {
        $this->app->bind('sitemap', 'lenal\sitemap\Helpers\SitemapHelper');
    }*/

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/sitemap.php') => config_path('sitemap.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/sitemap/') => base_path('packages/lenal/sitemap')
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SitemapGenerate::class,
            ]);
        }
    }
}
