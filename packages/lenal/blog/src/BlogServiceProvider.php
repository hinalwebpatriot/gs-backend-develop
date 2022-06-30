<?php

namespace lenal\blog;

use Illuminate\Support\ServiceProvider;
use lenal\blog\Models\Article;
use lenal\blog\Observers\ArticleObserver;

class BlogServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('blog', 'lenal\blog\Helpers\Blog');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/blog.php') => config_path('blog.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/blog/') => base_path('packages/lenal/blog')
        ]);

        Article::observe(ArticleObserver::class);
    }
}
