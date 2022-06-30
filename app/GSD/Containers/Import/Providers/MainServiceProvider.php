<?php

namespace GSD\Containers\Import\Providers;


use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Models\ReferralTransaction;
use GSD\Containers\Referral\Observers\PromoCodeObserver;
use GSD\Containers\Referral\Observers\TransactionObserver;
use GSD\Core\Loaders\MigrationsLoaderTrait;
use GSD\Ship\Core\Loaders\CommandsLoaderTrait;
use GSD\Ship\Core\Loaders\ConfigsLoaderTrait;
use GSD\Ship\Core\Loaders\RoutersLoaderTrait;
use GSD\Ship\Parents\Providers\MainProvider as ParentMainProvider;

/**
 * Class MainServiceProvider
 * @package GSD\Containers\Import\Providers
 */
class MainServiceProvider extends ParentMainProvider
{
    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'Import';

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
