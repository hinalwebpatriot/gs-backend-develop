<?php

namespace App\Providers;

use App\Services\SphinxEngine;
use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;
use Laravel\Scout\EngineManager;

class SphinxProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        resolve(EngineManager::class)->extend('sphinx', function ($app) {
            $options = config('scout.sphinx');
            if (!$options['socket']) {
                unset($options['socket']);
            }

            $connection = new Connection();
            $connection->setParams($options);

            return new SphinxEngine(new SphinxQL($connection));
        });

        /*Builder::macro('whereIn', function (string $attribute, array $arrayIn) {
            $this->engine()->addWhereIn($attribute, $arrayIn);
            return $this;
        });*/
    }
}
