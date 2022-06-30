<?php

namespace App\Providers;

use App\Nova\Article;
use App\Nova\BlogCategory;
use App\Nova\Brand;
use App\Nova\CartItem;
use App\Nova\Category;
use App\Nova\Clarity;
use App\Nova\Color;
use App\Nova\Culet;
use App\Nova\CurrencyRate;
use App\Nova\Cut;
use App\Nova\DeliverySchema;
use App\Nova\Diamond;
use App\Nova\DiamondLab;
use App\Nova\DynamicPage;
use App\Nova\EngagementRing;
use App\Nova\EngagementRingStyle;
use App\Nova\FAQ;
use App\Nova\FilterDescription;
use App\Nova\Fluorescence;
use App\Nova\Landing;
use App\Nova\Location;
use App\Nova\MainSliderSlide;
use App\Nova\MainSliderSlider;
use App\Nova\MainSliderSliderMobile;
use App\Nova\MainSliderVideo;
use App\Nova\Manufacturer;
use App\Nova\MenuDropdownContent;
use App\Nova\Metal;
use App\Nova\Metrics\DiamondsByManufacturer;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersStatus;
use App\Nova\Observers\DiamondObserver;
use App\Nova\Observers\InvoiceObserver;
use App\Nova\Offer;
use App\Nova\OfferBrands;
use App\Nova\Order;
use App\Nova\Paysystem;
use App\Nova\Polish;
use App\Nova\Product;
use App\Nova\ProductCategory;
use App\Nova\ProductCustomField;
use App\Nova\ProductHint;
use App\Nova\ProductSize;
use App\Nova\ProductStyle;
use App\Nova\Promocode;
use App\Nova\PromoRegistration;
use App\Nova\PromoRegistrationText;
use App\Nova\Review;
use App\Nova\RingCollection;
use App\Nova\RingSize;
use App\Nova\SEOBlock;
use App\Nova\SEOMeta;
use App\Nova\SeoRedirect;
use App\Nova\Shape;
use App\Nova\ShowRoomCountries;
use App\Nova\ShowRooms;
use App\Nova\StaticPages;
use App\Nova\Status;
use App\Nova\Subscribe;
use App\Nova\SubscribeBanner;
use App\Nova\SupportContact;
use App\Nova\Symmetry;
use App\Nova\User;
use App\Nova\WeddingRing;
use App\Nova\WeddingRingStyle;
use ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\Group;
use DigitalCreative\CollapsibleResourceManager\Resources\NovaResource;
use DigitalCreative\CollapsibleResourceManager\Resources\RawResource;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use Eminiarts\NovaPermissions\NovaPermissions;
use GSD\Containers\Import\Nova\ImportLog;
use GSD\Containers\Import\Nova\ImportSchedule;
use GSD\Containers\Import\Nova\ImportStatistic;
use GSD\Libs\NovaTools\MarginCalc\MarginCalc;
use GSD\Containers\Referral\Nova\ReferralCustomer;
use GSD\Containers\Referral\Nova\ReferralPayoutTransaction;
use GSD\Containers\Referral\Nova\ReferralPromoCode;
use GSD\Containers\Referral\Nova\ReferralTransaction;
use GSD\Containers\Referral\Nova\TowerCustomer;
use HasManySelectField\FieldServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Observable;
use Laravel\Nova\Tool;
use lenal\catalog\Models\Invoice;
use lenal\MarginCalculateTool\MarginCalculateTool;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::serving(function () {
            Invoice::observe(InvoiceObserver::class);
        });
        Observable::make(\lenal\catalog\Models\Diamonds\Diamond::class, DiamondObserver::class);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function (\App\User $user) {
            return $user->hasNovaAccess();
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new OrdersPerDay,
            new OrdersStatus,
            new DiamondsByManufacturer,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new CollapsibleResourceManager([
                'disable_default_resource_manager' => true, // default
                'remember_menu_state'              => false, // default
                'navigation'                       => [
                    TopLevelResource::make([
                        'label'     => 'Products',
                        'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="20" viewBox="0 0 16 20"><defs><path id="eaqvc" d="M639.962 87.05a7.962 7.962 0 1 0 0 15.924 7.962 7.962 0 1 0 0-15.924m0 15.859a7.15 7.15 0 1 1 7.151-7.15 7.15 7.15 0 0 1-7.151 7.15"></path><path id="eaqvd" d="M639.955 88.437l1.911-4.297h-3.806z"></path><path id="eaqva" d="M638.08 83.11h3.768v.888h-3.768z"></path><path id="eaqvf" d="M640.944 83.11h-1.96l-.904.888h3.768z"></path><path id="eaqvg" d="M639.789 88.435l-4.039-4.295h2.126z"></path><path id="eaqvh" d="M640.13 88.435l4.039-4.295h-2.125z"></path><path id="eaqvb" d="M641.16 83.11h3.013v.888h-3.013z"></path><path id="eaqvj" d="M642.262 83.11h-1.102l.904.888h2.11z"></path><clipPath id="eaqve"><use xlink:href="#eaqva"></use></clipPath><clipPath id="eaqvi"><use xlink:href="#eaqvb"></use></clipPath></defs><g><g transform="translate(-632 -83)"><g><use fill="var(--sidebar-icon)" xlink:href="#eaqvc"></use></g><g><use fill="var(--sidebar-icon)" xlink:href="#eaqvd"></use></g><g><g></g><g clip-path="url(#eaqve)"><use fill="var(--sidebar-icon)" xlink:href="#eaqvf"></use></g></g><g><use fill="var(--sidebar-icon)" xlink:href="#eaqvg"></use></g><g><use fill="var(--sidebar-icon)" xlink:href="#eaqvh"></use></g><g><g></g><g clip-path="url(#eaqvi)"><use fill="var(--sidebar-icon)" xlink:href="#eaqvj"></use></g></g></g></g></svg>',
                        'resources' => [
                            Group::make([
                                'label'     => 'Engagement Rings',
                                'expanded'  => false,
                                'resources' => [
                                    EngagementRing::class,
                                    EngagementRingStyle::class
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Wedding Rings',
                                'expanded'  => false,
                                'resources' => [
                                    WeddingRing::class,
                                    WeddingRingStyle::class
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Jewelleries',
                                'expanded'  => false,
                                'resources' => [
                                    Product::class,
                                    Category::class
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Diamonds',
                                'expanded'  => false,
                                'resources' => [
                                    Diamond::class,
                                    DiamondLab::class,
                                    Manufacturer::class,
                                    Clarity::class,
                                    Color::class,
                                    Culet::class,
                                    Cut::class,
                                    Fluorescence::class,
                                    Polish::class,
                                    Shape::class,
                                    Symmetry::class,
                                ]
                            ]),
                            NovaResource::make(Brand::class),
                            NovaResource::make(RingCollection::class),
                            NovaResource::make(Offer::class),
                            NovaResource::make(OfferBrands::class),
                            Group::make([
                                'label'     => 'Properties',
                                'expanded'  => false,
                                'resources' => [
                                    Metal::class,
                                    RingSize::class,
                                    ProductSize::class,
                                    ProductStyle::class,
                                    ProductCustomField::class
                                ]
                            ])
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Referral Program',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"><g fill="var(--sidebar-icon)"><path d="M869.3,716.5c-60.1-134.8-192.6-230.2-348-237v-54.1c105-10.7,187-99.3,187-207.2C708.2,103.2,615,10,500,10s-208.2,93.2-208.2,208.2c0,106.8,80.4,194.8,184,206.8v55c-150.7,10.3-278.4,103.9-337.6,235.1c-66.5,10-117.5,67.4-117.5,136.7c0,76.3,61.9,138.2,138.2,138.2c76.3,0,138.2-61.9,138.2-138.2c0-69.7-51.7-127.4-118.8-136.8c56.2-110.8,167.3-189,297.5-198.8v199.6c-64.8,11.5-113.9,68-113.9,136.1c0,76.3,61.9,138.2,138.2,138.2c76.3,0,138.2-61.9,138.2-138.2c0-69.1-50.7-126.3-116.9-136.6V515.6C655.4,522,770.5,601,828.2,714.2C758,720.7,703,779.8,703,851.8c0,76.3,61.9,138.2,138.2,138.2c76.3,0,138.2-61.9,138.2-138.2C979.3,785.1,932.1,729.5,869.3,716.5z M257.8,851.8c0,54.6-44.3,98.9-98.9,98.9s-98.9-44.3-98.9-98.9c0-54.6,44.3-98.9,98.9-98.9C213.5,752.9,257.8,797.2,257.8,851.8z M335.1,218.2c0-91.1,73.8-164.9,164.9-164.9c91.1,0,164.9,73.8,164.9,164.9c0,91.1-73.8,164.9-164.9,164.9C408.9,383.1,335.1,309.3,335.1,218.2z M598.9,851.8c0,54.6-44.3,98.9-98.9,98.9c-54.6,0-98.9-44.3-98.9-98.9c0-54.6,44.3-98.9,98.9-98.9C554.6,752.9,598.9,797.2,598.9,851.8z M841.2,950.7c-54.6,0-98.9-44.3-98.9-98.9c0-54.6,44.3-98.9,98.9-98.9c54.6,0,98.9,44.3,98.9,98.9C940.1,906.4,895.8,950.7,841.2,950.7z"/></g></svg>',
                        'resources' => [
                            NovaResource::make(TowerCustomer::class),
                            NovaResource::make(ReferralCustomer::class),
                            NovaResource::make(ReferralPromoCode::class),
                            NovaResource::make(ReferralTransaction::class),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label'     => 'Shop',
                        'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 18 14"><defs><path id="l0rka" d="M1471 88.24h17.305v8.565H1471z"></path><path id="l0rke" d="M1488.132 88.24H1485.396a2.046 2.046 0 0 1-1.124 1.818 2.09 2.09 0 0 1-2.798-.883l-.489-.935h-2.663l-.488.935a2.09 2.09 0 0 1-2.798.883 2.047 2.047 0 0 1-1.124-1.818h-2.739a.173.173 0 0 0-.173.17v1.027c0 .094.078.17.173.17h1.103c.095 0 .194.075.22.165l1.954 6.869c.026.09.125.164.22.164h9.908a.243.243 0 0 0 .22-.164l1.991-6.869a.243.243 0 0 1 .221-.164h1.122a.172.172 0 0 0 .173-.171V88.41a.172.172 0 0 0-.173-.171"></path><path id="l0rkb" d="M1475.3 83.17h3.67v5.743h-3.67z"></path><path id="l0rkg" d="M1475.675 88.837a.695.695 0 0 0 .933-.294l2.285-4.375a.68.68 0 0 0-.298-.922.696.696 0 0 0-.933.295l-2.285 4.375a.68.68 0 0 0 .298.92"></path><path id="l0rkc" d="M1480.34 83.17h3.67v5.743h-3.67z"></path><path id="l0rki" d="M1482.702 88.543a.694.694 0 0 0 .933.294.68.68 0 0 0 .298-.921l-2.285-4.376a.696.696 0 0 0-.933-.294.68.68 0 0 0-.298.921z"></path><clipPath id="l0rkd"><use xlink:href="#l0rka"></use></clipPath><clipPath id="l0rkf"><use xlink:href="#l0rkb"></use></clipPath><clipPath id="l0rkh"><use xlink:href="#l0rkc"></use></clipPath></defs><g><g transform="translate(-1471 -83)"><g><g></g><g clip-path="url(#l0rkd)"><use fill="var(--sidebar-icon)" xlink:href="#l0rke"></use></g></g><g><g></g><g clip-path="url(#l0rkf)"><use fill="var(--sidebar-icon)" xlink:href="#l0rkg"></use></g></g><g><g></g><g clip-path="url(#l0rkh)"><use fill="var(--sidebar-icon)" xlink:href="#l0rki"></use></g></g></g></g></svg>',
                        'resources' => [
                            Group::make([
                                'label'     => 'Orders',
                                'expanded'  => false,
                                'resources' => [
                                    Order::class,
                                    \App\Nova\Invoice::class,
                                    CartItem::class,
                                    Status::class,
                                ]
                            ]),
                            NovaResource::make(DeliverySchema::class),
                            NovaResource::make(Promocode::class),
                            NovaResource::make(Paysystem::class),
                            NovaResource::make(Review::class),
                            NovaResource::make(ProductHint::class),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label'     => 'SEO',
                        'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 14 11"><defs><path id="h2z8a" d="M1433.873 85c-.73 0-1.398.214-1.988.637a4.282 4.282 0 0 0-1.163 1.296 4.282 4.282 0 0 0-1.163-1.296 3.347 3.347 0 0 0-1.988-.637c-2.036 0-3.571 1.543-3.571 3.59 0 2.211 1.915 3.724 4.815 6.014.492.389 1.05.83 1.63 1.3a.44.44 0 0 0 .277.096.44.44 0 0 0 .277-.096c.58-.47 1.138-.911 1.63-1.3 2.9-2.29 4.815-3.803 4.815-6.014 0-2.047-1.535-3.59-3.57-3.59z"></path></defs><g><g transform="translate(-1424 -85)"><use fill="var(--sidebar-icon)" xlink:href="#h2z8a"></use></g></g></svg>',
                        'resources' => [
                            NovaResource::make(SEOBlock::class),
                            NovaResource::make(SEOMeta::class),
                            NovaResource::make(SeoRedirect::class),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label'     => 'Site',
                        'item'      => '<svg width="20" height="17" viewBox="0 0 20 17"><defs><path id="uvh0a" d="M496.625 86.218L499.728 85h8.49l2.89 1.218-3.012 1.25h-8.49l-2.981-1.25m-.678.532l3.377 1.374-.76 3.062-4.564-2 1.947-2.436m-1.697 3.375l4.29 2 4.351 8.87-8.641-10.87m10.75 10.87l4.44-8.87 4.201-2-8.641 10.87m3.53-9.809l-9.28.031.76-2.842h7.79m.825-.22l3.499-1.28 1.795 2.249-4.444 2.062-.85-3.03m-9.375 4.094h9.28l-4.594 9.246-4.686-9.246"></path></defs><g><g transform="translate(-494 -85)"><use fill="var(--sidebar-icon)" xlink:href="#uvh0a"></use></g></g></svg>',
                        'resources' => [
                            Group::make([
                                'label'     => 'Blog',
                                'expanded'  => false,
                                'resources' => [
                                    Article::class,
                                    BlogCategory::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Static Content',
                                'expanded'  => false,
                                'resources' => [
                                    DynamicPage::class,
                                    StaticPages::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'ShowRooms',
                                'expanded'  => false,
                                'resources' => [
                                    ShowRooms::class,
                                    ShowRoomCountries::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Main Slider',
                                'expanded'  => false,
                                'resources' => [
                                    MainSliderSlider::class,
                                    MainSliderSlide::class,
                                    MainSliderSliderMobile::class,
                                    MainSliderVideo::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Design',
                                'expanded'  => false,
                                'resources' => [
                                    FilterDescription::class,
                                    MenuDropdownContent::class,
                                    ProductCategory::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Popup Promo',
                                'expanded'  => false,
                                'resources' => [
                                    PromoRegistrationText::class,
                                    PromoRegistration::class,
                                ]
                            ]),
                            Group::make([
                                'label'     => 'Subscribes',
                                'expanded'  => false,
                                'resources' => [
                                    Subscribe::class,
                                    SubscribeBanner::class,
                                ]
                            ]),
                            NovaResource::make(SupportContact::class),
                            NovaResource::make(FAQ::class),
                            NovaResource::make(Landing::class),
                            NovaResource::make(User::class),
                            Group::make([
                                'label'     => 'Settings',
                                'expanded'  => false,
                                'resources' => [
                                    NovaResource::make(CurrencyRate::class),
                                    NovaResource::make(Location::class),
                                ]
                            ]),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Import',
                        'icon' => '<svg width="26" height="26" viewBox="0 0 48 48"><g id="layer1" transform="translate(0 -1004.4)"><path fill="var(--sidebar-icon)" d="m22.44 1013.5c-8.0402 0-14.562 6.5224-14.562 14.562 0 8.0403 6.5223 14.562 14.562 14.562 5.9045 0 10.966-3.5108 13.25-8.5625-1.1115-0.3397-2.2439-0.6279-3.375-0.7813-1.8698 3.5451-5.5884 5.9375-9.875 5.9375-6.1641 0-11.156-4.992-11.156-11.156s4.9921-11.156 11.156-11.156c4.8556 0 8.9647 3.1083 10.5 7.4375 1.3847 0.6063 2.704 1.3047 3.9375 2.0938-0.81737-7.2654-6.9537-12.938-14.438-12.938zm1.4688 5.4062c-0.52244 0.044-1.499 0.8157-3.3125 2.4063-7.9322 6.9574-7.8483 7.4501-7.8438 8.0312l0.0625 0.2813c0.29726 0.5309 0.64434 0.7382 4.3125 3.0625 1.0232 0.6483 3.1308 2.0128 4.6875 3.0312 3.8686 2.5309 4.0626 2.5056 3.8438-0.4687-0.12949-1.7598-0.12283-1.8904 0.1875-2.25 0.28212-0.3269 0.5541-0.4059 1.8125-0.5938 3.4384-0.5136 7.1965 0.033 10.531 1.5313 1.392 0.6253 1.8228 0.8541 3.3125 1.875 0.714 0.4892 0.97917 0.6031 1.5312 0.5625 0.49958-0.036 0.67926-0.1453 0.78125-0.375 0.52901-1.1912-1.2238-3.6647-4.7188-6.6563-3.5199-3.0131-7.5039-4.8836-12.25-5.7187-2.0303-0.3573-2.0528-0.3693-2.2188-2.625-0.10523-1.4302-0.19631-2.1375-0.71875-2.0938z"/></g></svg>',
                        'resources' => [
                            NovaResource::make(ImportSchedule::class),
                            NovaResource::make(ImportLog::class),
                            NovaResource::make(ImportStatistic::class),
                        ]
                    ]),
                ]
            ]),
            (new MarginCalc())->canSee(function ($request) {
                if ($request->user()->isSuperAdmin()) {
                    return true;
                }
                if ($request->user()->isAdmin()) {
                    return true;
                }
                if ($request->user()->isCallCenter()) {
                    return true;
                }
                return false;
            }),

            (new MarginCalculateTool())->canSee(function ($request) {
                if ($request->user()->isSuperAdmin()) {
                    return true;
                }
                if ($request->user()->isAdmin()) {
                    return true;
                }
                if ($request->user()->isCallCenter()) {
                    return true;
                }
                return false;
            }),
            /*(new FilemanagerTool())->canSee(function ($request) {
                if ($request->user()->isSuperAdmin()) {
                    return $request->user()->isSuperAdmin();
                }
                if ($request->user()->isAdmin()) {
                    return $request->user()->isAdmin();
                }
            }),*/
            (new NovaPermissions())->canSee(function ($request) {
                return $request->user()->isSuperAdmin();
            }),
            NovaBreadcrumbs::make(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(FieldServiceProvider::class);
        $this->app->register(\Vpsitua\HasManySinglePage\FieldServiceProvider::class);
    }

    /**
     * Register the application's Nova resources.
     *
     * @return void
     */
    protected function resources()
    {
        Nova::resourcesIn(app_path('Nova'));

        Nova::resources([
            TowerCustomer::class,
            ReferralCustomer::class,
            ReferralPromoCode::class,
            ReferralTransaction::class,
            ReferralPayoutTransaction::class,
            ImportSchedule::class,
            ImportLog::class,
            ImportStatistic::class,
        ]);
    }
}
