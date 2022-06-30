<?php

namespace lenal\catalog;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use lenal\catalog\Commands\ConfigImageResize;
use lenal\catalog\Commands\CopyNewFormatImage;
use lenal\catalog\Commands\DiamondBrandRemoveCommand;
use lenal\catalog\Commands\ResizeDiamondImage;
use lenal\catalog\Commands\ShippingCommand;
use lenal\catalog\Helpers\CartHelper;
use lenal\catalog\Helpers\OrderHelper;
use lenal\catalog\Helpers\CommonHelper;
use lenal\catalog\Helpers\ConstructorHelper;
use lenal\catalog\Helpers\DiamondsHelper;
use lenal\catalog\Helpers\EngagementsHelper;
use lenal\catalog\Helpers\FavoritesCompareHelper;
use lenal\catalog\Helpers\FilterBuilderHelper;
use lenal\catalog\Helpers\PaymentHelper;
use lenal\catalog\Helpers\WeddingsHelper;
use lenal\catalog\Middleware\CatalogStore;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Paysystem;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Observers\DiamondObserver;
use lenal\catalog\Observers\EngagementRingObserver;
use lenal\catalog\Observers\MarginCalculateObserver;
use lenal\catalog\Observers\OffersObserver;
use lenal\catalog\Observers\ProductObserver;
use lenal\catalog\Observers\WeddingRingObserver;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\catalog\Services\Payments\AlipayPayment;
use lenal\MarginCalculate\Models\MarginCalculate;
use lenal\catalog\Commands\ResizeImage;
use lenal\offers\Models\Offer;

/**
 * Class CatalogServiceProvider
 *
 * @package lenal\catalog
 */
class CatalogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('diamonds_helper', DiamondsHelper::class);
        $this->app->bind('engagements_helper', EngagementsHelper::class);
        $this->app->bind('weddings_helper', WeddingsHelper::class);
        $this->app->bind('filter_builder_helper', FilterBuilderHelper::class);
        $this->app->bind('catalog_store_middleware', CatalogStore::class);
        $this->app->bind('catalog_common_helper', CommonHelper::class);
        $this->app->bind('catalog_constructor_helper', ConstructorHelper::class);
        $this->app->bind('catalog_cart_helper', CartHelper::class);
        $this->app->bind('catalog_order_helper', OrderHelper::class);
        $this->app->bind('catalog_fav_compare_helper', FavoritesCompareHelper::class);
        $this->app->bind('payment_helper', PaymentHelper::class);

        $this->app->singleton(DeliveryTimeService::class);

        $this->app->bind('alipay', function() {
            $credentials = Paysystem::findBySlug('alipay')->parseCredentials();
            return new AlipayPayment(
                Arr::get($credentials, 'merchant_id'),
                Arr::get($credentials, 'authentication_code')
            );
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
        $this->loadViewsFrom(realpath(dirname(__FILE__)) . '/views', 'catalog');

        $this->publishes([
            realpath(dirname(__FILE__) . '/config/catalog.php') => config_path('catalog.php')
        ], 'config');

        $this->publishes([
            base_path('vendor/lenal/catalog/') => base_path('packages/lenal/catalog')
        ]);

        Diamond::observe(DiamondObserver::class);
        WeddingRing::observe(WeddingRingObserver::class);
        EngagementRing::observe(EngagementRingObserver::class);
        MarginCalculate::observe(MarginCalculateObserver::class);
        Offer::observe(OffersObserver::class);
        Product::observe(ProductObserver::class);


        if ($this->app->runningInConsole()) {
            $this->commands([
                ResizeImage::class,
                ShippingCommand::class,
                DiamondBrandRemoveCommand::class,
                ResizeDiamondImage::class,
                ConfigImageResize::class,
                CopyNewFormatImage::class
            ]);
        }
    }
}
