<?php

namespace GSD\Containers\Prerender\Providers;


use GSD\Containers\Prerender\Components\PrerenderClient\PrerenderClient;
use GSD\Ship\Core\Loaders\CommandsLoaderTrait;
use GSD\Ship\Core\Loaders\ConfigsLoaderTrait;
use GSD\Ship\Parents\Providers\MainProvider as ParentMainProvider;

/**
 * Class MainServiceProvider
 * @package GSD\Containers\Prerender\Providers
 */
class MainServiceProvider extends ParentMainProvider
{
    use CommandsLoaderTrait;
    use ConfigsLoaderTrait;

    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'Prerender';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();

        $this->loadContainerCommands($this->containerName);
        $this->loadContainerConfigs($this->containerName);
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(PrerenderClient::class, function ($app) {
            return new PrerenderClient(config('prerender.main.prerender_token'));
        });
    }
}
