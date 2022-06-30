<?php

namespace GSD\Core\Providers;

use GSD\Ship\Core\Facades\AppGSD;
use GSD\Core\Abstracts\Providers\MainProvider as AbstractMainProvider;
use GSD\Core\Loaders\ProvidersLoaderTrait;
use Illuminate\Support\Facades\Schema;

/**
 * Class AppProviders
 *
 * Does not have to extend from the Ship parent MainProvider since it's on the Core
 * it directly extends from the Abstract MainProvider.
 */
class AppProvider extends AbstractMainProvider
{
    use ProvidersLoaderTrait;

    /**
     * Register any Service Providers on the Ship layer (including third party packages).
     *
     * @var array
     */
    public array $serviceProviders = [
    ];

    /**
     * Register any Alias on the Ship layer (including third party packages).
     *
     * @var  array
     */
    protected array $aliases = [
        'AppGSD' => AppGSD::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // load all service providers defined in this class
        parent::boot();

        // Solves the "specified key was too long" error, introduced in L5.4
        Schema::defaultStringLength(191);

        foreach (AppGSD::getContainersNames() as $name) {
            $this->loadOnlyMainProvidersFromContainers($name);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }
}
