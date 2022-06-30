<?php

namespace App\Providers;


use App\Services\Shipping\Shipping;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ShippingProvider extends ServiceProvider
{
    public $defer = true;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Route::get('shipping/{service}', 'ShippingController@index');

        $this->app->bind(Shipping::class, function(Application $app) {
            try {
                $service = ucfirst(Route::current()->parameter('service'));
                return $app->make('App\Services\shipping\\' . $service);
            } catch (\Exception $e) {
                logger($e);
                $app->abort(404);
            }

            return null;
        });
    }

    public function provides()
    {
        return [Shipping::class];
    }
}