<?php

namespace GSD\Containers\Referral\Providers;


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
 * @package GSD\Containers\Referal\Providers
 */
class MainServiceProvider extends ParentMainProvider
{

    use MigrationsLoaderTrait;
    use RoutersLoaderTrait;
    use CommandsLoaderTrait;
    use ConfigsLoaderTrait;

    /**
     * Container Service Providers.
     *
     * @var array
     */
    public array $serviceProviders = [];

    private string $containerName = 'Referral';

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();

        ReferralPromoCode::observe(PromoCodeObserver::class);
        ReferralTransaction::observe(TransactionObserver::class);

        $this->loadViewsFrom(__DIR__.'/../Mails/Templates', 'ReferralEmails');
        $this->loadContainerCommands($this->containerName);
        $this->loadContainerMigrations($this->containerName);
        $this->loadContainerRoutes($this->containerName);
        $this->loadContainerConfigs($this->containerName);
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();
    }
}
