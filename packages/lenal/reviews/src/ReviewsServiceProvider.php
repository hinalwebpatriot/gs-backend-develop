<?php

namespace lenal\reviews;

use Illuminate\Support\ServiceProvider;

class ReviewsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('reviews', 'lenal\reviews\Helpers\ReviewsHelper');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            base_path('vendor/lenal/reviews/') => base_path('packages/lenal/reviews')
        ]);
    }
}
