<?php

namespace lenal\search;

use Illuminate\Support\ServiceProvider;
use lenal\search\Commands\DiamondAlgoliaIndex;

class SearchServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('algolia-search', 'lenal\search\Helpers\SearchHelper');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                DiamondAlgoliaIndex::class,
            ]);
        }
    }
}
