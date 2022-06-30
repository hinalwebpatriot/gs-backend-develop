<?php

namespace GSD\Core\Abstracts\Providers;

use GSD\Core\Loaders\AliasesLoaderTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;

/**
 * Class MainProvider
 */
abstract class MainProvider extends LaravelAppServiceProvider
{
    use AliasesLoaderTrait;

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->loadServiceProviders();
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {

    }

    /**
     * @param $providerFullName
     */
    private function loadProvider($providerFullName)
    {
        App::register($providerFullName);
    }

    /**
     * Load the all the registered Service Providers on the Main Service Provider.
     *
     * @void
     */
    public function loadServiceProviders()
    {
        foreach ($this->serviceProviders as $provider) {
            $this->loadProvider($provider);
        }
    }
}
