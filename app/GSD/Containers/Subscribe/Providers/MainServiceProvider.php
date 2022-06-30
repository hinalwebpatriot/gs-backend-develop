<?php

namespace GSD\Containers\Subscribe\Providers;


use GSD\Ship\Parents\Providers\MainProvider as ParentMainProvider;

/**
 * Class MainServiceProvider
 * @package GSD\Containers\Subscribe\Providers
 */
class MainServiceProvider extends ParentMainProvider
{
    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'Subscribe';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();
    }
}
