<?php

namespace GSD\Containers\Order\Providers;


use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Models\ReferralTransaction;
use GSD\Containers\Referral\Observers\PromoCodeObserver;
use GSD\Containers\Referral\Observers\TransactionObserver;
use GSD\Core\Loaders\MigrationsLoaderTrait;
use GSD\Ship\Core\Loaders\CommandsLoaderTrait;
use GSD\Ship\Core\Loaders\ConfigsLoaderTrait;
use GSD\Ship\Core\Loaders\RoutersLoaderTrait;
use GSD\Ship\Parents\Providers\MainProvider as ParentMainProvider;
use Illuminate\Support\Facades\Log;

/**
 * Class MainServiceProvider
 * @package GSD\Containers\Order\Providers
 */
class MainServiceProvider extends ParentMainProvider
{
    use MigrationsLoaderTrait;

    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'Order';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();
        $this->loadContainerMigrations($this->containerName);
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();
    }
}
