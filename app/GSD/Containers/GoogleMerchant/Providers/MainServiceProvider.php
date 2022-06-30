<?php

namespace GSD\Containers\GoogleMerchant\Providers;


use GSD\Ship\Core\Loaders\CommandsLoaderTrait;
use GSD\Ship\Parents\Providers\MainProvider as ParentMainProvider;

/**
 * Class MainServiceProvider
 * @package GSD\Containers\GoogleMerchant\Providers
 */
class MainServiceProvider extends ParentMainProvider
{
    use CommandsLoaderTrait;

    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'GoogleMerchant';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../Views', 'GoogleMerchant');
        $this->loadContainerCommands($this->containerName);
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();
    }
}
