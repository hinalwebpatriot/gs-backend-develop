<?php

namespace App\Providers;


use App\Policies\ArticleDetailBlockPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\BannerPolicy;
use App\Policies\BlogCategoryPolicy;
use App\Policies\CartItemPolicy;
use App\Policies\ClarityPolicy;
use App\Policies\ColorPolicy;
use App\Policies\ContactLinkPolicy;
use App\Policies\CountryVatPolicy;
use App\Policies\CuletPolicy;
use App\Policies\CurrencyRatePolicy;
use App\Policies\CutPolicy;
use App\Policies\DiamondPolicy;
use App\Policies\DynamicPagePolicy;
use App\Policies\EngagementRingPolicy;
use App\Policies\EngagementRingStylePolicy;
use App\Policies\FAQPolicy;
use App\Policies\FilterDescriptionPolicy;
use App\Policies\FluorescencePolicy;
use App\Policies\LocationPolicy;
use App\Policies\MainSliderPolicy;
use App\Policies\MainSliderSlidePolicy;
use App\Policies\MainSliderVideoPolicy;
use App\Policies\ManufacturerPolicy;
use App\Policies\MenuDropdownContentPolicy;
use App\Policies\MetalPolicy;
use App\Policies\MetaPolicy;
use App\Policies\OfferPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaysystemPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\Policy;
use App\Policies\PolishPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductHintPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\RingCollectionPolicy;
use App\Policies\RingSizePolicy;
use App\Policies\RolePolicy;
use App\Policies\SEOBlockPolicy;
use App\Policies\ShapePolicy;
use App\Policies\ShowRoomCountryPolicy;
use App\Policies\ShowRoomPolicy;
use App\Policies\StaticBlockPolicy;
use App\Policies\StaticPagesPolicy;
use App\Policies\StatusPolicy;
use App\Policies\SubscribePolicy;
use App\Policies\SymmetryPolicy;
use App\Policies\UserPolicy;
use App\Policies\WeddingRingPolicy;
use App\Policies\WeddingRingStylePolicy;
use App\User;
use Carbon\Carbon;
use GSD\Containers\Import\Models\ImportLog;
use GSD\Containers\Import\Models\ImportStatistic;
use GSD\Containers\Import\Policies\ImportLogPolicy;
use GSD\Containers\Import\Policies\ImportStatisticPolicy;
use GSD\Containers\Referral\Models\ReferralPayoutTransaction;
use GSD\Containers\Referral\Policies\ReferralPayoutTransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use lenal\additional_content\Models\FAQ;
use lenal\additional_content\Models\MenuDropdownContent;
use lenal\AppSettings\Models\Location;
use lenal\banners\Models\Banner;
use lenal\blocks\Models\DynamicPage;
use lenal\blocks\Models\StaticBlock;
use lenal\blog\Models\Article;
use lenal\blog\Models\ArticleDetailBlock;
use lenal\blog\Models\BlogCategory;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Culet;
use lenal\catalog\Models\Diamonds\Cut;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Fluorescence;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\catalog\Models\Diamonds\Polish;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Diamonds\Symmetry;
use lenal\catalog\Models\FilterDescription;
use lenal\catalog\Models\Order;
use lenal\catalog\Models\Paysystem;
use lenal\catalog\Models\ProductCategory;
use lenal\catalog\Models\ProductHint;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\Metal;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Models\Rings\WeddingRingStyle;
use lenal\catalog\Models\Status;
use lenal\MainSlider\Models\MainSlider;
use lenal\MainSlider\Models\MainSliderSlide;
use lenal\MainSlider\Models\MainSliderVideo;
use lenal\offers\Models\Offer;
use lenal\PriceCalculate\Models\CountryVat;
use lenal\PriceCalculate\Models\CurrencyRate;
use lenal\reviews\Models\Review;
use lenal\seo\Models\Meta;
use lenal\seo\Models\SEOBlock;
use lenal\ShowRooms\Models\ShowRoom;
use lenal\ShowRooms\Models\ShowRoomCountry;
use lenal\social\Models\ContactLink;
use lenal\static_pages\Models\StaticPage;
use lenal\subscribe\Models\Subscribe;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class                => UserPolicy::class,
        Role::class                => RolePolicy::class,
        Permission::class          => PermissionPolicy::class,
        Article::class             => ArticlePolicy::class,
        ArticleDetailBlock::class  => ArticleDetailBlockPolicy::class,
        Banner::class              => BannerPolicy::class,
        BlogCategory::class        => BlogCategoryPolicy::class,
        CartItem::class            => CartItemPolicy::class,
        Clarity::class             => ClarityPolicy::class,
        Color::class               => ColorPolicy::class,
        CountryVat::class          => CountryVatPolicy::class,
        Culet::class               => CuletPolicy::class,
        CurrencyRate::class        => CurrencyRatePolicy::class,
        Cut::class                 => CutPolicy::class,
        Diamond::class             => DiamondPolicy::class,
        DynamicPage::class         => DynamicPagePolicy::class,
        EngagementRing::class      => EngagementRingPolicy::class,
        EngagementRingStyle::class => EngagementRingStylePolicy::class,
        FAQ::class                 => FAQPolicy::class,
        FilterDescription::class   => FilterDescriptionPolicy::class,
        Fluorescence::class        => FluorescencePolicy::class,
        Location::class            => LocationPolicy::class,
        MainSliderSlide::class     => MainSliderSlidePolicy::class,
        MainSlider::class          => MainSliderPolicy::class,
        MainSliderVideo::class     => MainSliderVideoPolicy::class,
        Manufacturer::class        => ManufacturerPolicy::class,
        MenuDropdownContent::class => MenuDropdownContentPolicy::class,
        Metal::class               => MetalPolicy::class,
        Offer::class               => OfferPolicy::class,
        Order::class               => OrderPolicy::class,
        Paysystem::class           => PaysystemPolicy::class,
        Polish::class              => PolishPolicy::class,
        ProductCategory::class     => ProductCategoryPolicy::class,
        ProductHint::class         => ProductHintPolicy::class,
        Review::class              => ReviewPolicy::class,
        RingCollection::class      => RingCollectionPolicy::class,
        RingSize::class            => RingSizePolicy::class,
        SEOBlock::class            => SEOBlockPolicy::class,
        Meta::class                => MetaPolicy::class,
        Shape::class               => ShapePolicy::class,
        ShowRoomCountry::class     => ShowRoomCountryPolicy::class,
        ShowRoom::class            => ShowRoomPolicy::class,
        StaticBlock::class         => StaticBlockPolicy::class,
        StaticPage::class          => StaticPagesPolicy::class,
        Status::class              => StatusPolicy::class,
        Subscribe::class           => SubscribePolicy::class,
        ContactLink::class         => ContactLinkPolicy::class,
        Symmetry::class            => SymmetryPolicy::class,
        WeddingRing::class         => WeddingRingPolicy::class,
        WeddingRingStyle::class    => WeddingRingStylePolicy::class,

        ReferralPayoutTransaction::class => ReferralPayoutTransactionPolicy::class,
        ImportLog::class                 => ImportLogPolicy::class,
        ImportStatistic::class           => ImportStatisticPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addWeek(1));
        Passport::refreshTokensExpireIn(Carbon::now()->addWeek(1));
    }
}
