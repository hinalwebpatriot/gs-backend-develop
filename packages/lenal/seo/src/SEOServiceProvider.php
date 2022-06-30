<?php

namespace lenal\seo;

use Illuminate\Support\ServiceProvider;
use lenal\seo\Models\SEOBlock;
use lenal\seo\Observers\SEOBlockObserver;

class SEOServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('seo', 'lenal\seo\Helpers\SEO');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/seo.php') => config_path('seo.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/seo/') => base_path('packages/lenal/seo')
        ]);

        SEOBlock::observe(SEOBlockObserver::class);
    }
}
