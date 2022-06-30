<?php

use Illuminate\Support\Facades\Route;
use lenal\catalog\Controllers\DiamondsController;
use \lenal\catalog\Controllers\WeddingsController;
use \lenal\catalog\Controllers\EngagementsController;

Route::prefix('api')->group(function(){
    Route::post('/diamonds/get', 'lenal\catalog\Controllers\DiamondsController@index');
    Route::post('/diamonds/del/{sku}', 'lenal\catalog\Controllers\DiamondsController@delete');
    Route::get('/diamonds/{id}', 'lenal\catalog\Controllers\DiamondsController@show')
        ->middleware('qcookie');

    Route::resource('diamonds', DiamondsController::class)
        ->middleware('catalog_store_middleware')
        ->only('store');
    // If we use resource with PUT request to update model, it won't see the file
    // https://github.com/laravel/framework/issues/13457
    Route::post('diamonds/{sku}', 'lenal\catalog\Controllers\DiamondsController@update')
        ->middleware('catalog_store_middleware');

    Route::get('/diamonds-filters', 'lenal\catalog\Controllers\DiamondsController@getFilters');
    Route::get('/diamonds/presence-list/{manufacturer}', 'lenal\catalog\Controllers\DiamondsController@presenceList');

    // ---------------------------- Engagement rings routes --------------------------------
    Route::resource('engagement-rings', EngagementsController::class)
        ->only('index', 'show')
        ->middleware('qcookie');
    Route::post('engagement-rings/get', 'lenal\catalog\Controllers\EngagementsController@index');
    Route::post('engagement-rings/feed', 'lenal\catalog\Controllers\EngagementsController@feed');
    Route::resource('engagement-rings', EngagementsController::class)
        ->middleware('catalog_store_middleware')
        ->only('store');
    // If we use resource with PUT request to update model, it won't see the file
    // https://github.com/laravel/framework/issues/13457
    Route::post('engagement-rings/{sku}', 'lenal\catalog\Controllers\EngagementsController@update')
        ->middleware('catalog_store_middleware');

    Route::get('engagement-rings-filters', 'lenal\catalog\Controllers\EngagementsController@getFilters');
    // ---------------------------- End engagement rings routes --------------------------------


    Route::group(['prefix' => 'products', 'namespace' => 'lenal\catalog\Controllers'], function() {
        Route::get('categories', 'ProductsController@categories');
        Route::get('filters/{category}', 'ProductsController@filters');

        Route::middleware(['qcookie'])->group(function() {
            Route::get('category/{category}', 'ProductsController@index');
            Route::get('show/{id}', 'ProductsController@show');
        });
    });

    // ---------------------------- Wedding rings routes --------------------------------
    Route::resource('wedding-rings', WeddingsController::class)
        ->only('index', 'show')
        ->middleware('qcookie');
    Route::post('wedding-rings/get', 'lenal\catalog\Controllers\WeddingsController@index');
    Route::resource('wedding-rings', WeddingsController::class)
        ->middleware('catalog_store_middleware')
        ->only('store');
    Route::post('weddings/import', 'lenal\catalog\Controllers\WeddingsController@import')
        ->middleware('catalog_store_middleware');
    // If we use resource with PUT request to update model, it won't see the file
    // https://github.com/laravel/framework/issues/13457
    Route::post('wedding-rings/{sku}', 'lenal\catalog\Controllers\WeddingsController@update')
        ->middleware('catalog_store_middleware');

    Route::get('wedding-rings-filters', 'lenal\catalog\Controllers\WeddingsController@getFilters');
    // ---------------------------- End wedding rings routes --------------------------------

    Route::get('/shapes-banner', 'lenal\catalog\Controllers\DiamondsController@shapesBanner');
    Route::get('/product-categories/all', 'lenal\catalog\Controllers\DiamondsController@productCategories');
    Route::get(
        '/product-categories/suggested',
        'lenal\catalog\Controllers\DiamondsController@productCategoriesSuggested'
    );


    Route::prefix('favorites')->group(function(){
        Route::post('/add/{type}', 'lenal\catalog\Controllers\CommonController@addToFavorites');
        Route::post('/remove/{type}', 'lenal\catalog\Controllers\CommonController@removeFromFavorites');
        Route::post('/remove-all/{type?}', 'lenal\catalog\Controllers\CommonController@removeAllFavorites')
            ->middleware('qcookie');

        Route::get('/all', 'lenal\catalog\Controllers\CommonController@favorites');
        Route::post('/save', 'lenal\catalog\Controllers\CommonController@favoritesSave'); // save all as share list

        Route::post('/share/{type}', 'lenal\catalog\Controllers\CommonController@favoritesShare'); // save category as share list and share by email

        Route::get('/{type}', 'lenal\catalog\Controllers\CommonController@favorites');
    });

    Route::prefix('compare')->group(function(){

        Route::post('/add/{type}', 'lenal\catalog\Controllers\CommonController@addToCompare');
        Route::post('/remove/{type}', 'lenal\catalog\Controllers\CommonController@removeFromCompare');
        Route::post('/remove-all/{type?}', 'lenal\catalog\Controllers\CommonController@removeAllCompares')
            ->middleware('qcookie');

        Route::get('/all', 'lenal\catalog\Controllers\CommonController@compares');
        Route::post('/save', 'lenal\catalog\Controllers\CommonController@comparesSave');

        Route::post('/share/{type}', 'lenal\catalog\Controllers\CommonController@comparesShare');

        Route::get('/{type}', 'lenal\catalog\Controllers\CommonController@comparesList');

    });

    Route::prefix('cart')->group(function() {
        Route::post('/add/{type}', 'lenal\catalog\Controllers\CommonController@addToCart');
        Route::post('/remove/{type}', 'lenal\catalog\Controllers\CommonController@removeFromCart');
        Route::get('/get', 'lenal\catalog\Controllers\CommonController@getCart');
        Route::post('/get', 'lenal\catalog\Controllers\CommonController@getAllCart');
    });

    Route::prefix('order')->group(function() {
        Route::post('/create', 'lenal\catalog\Controllers\CommonController@createOrder');
        Route::post('/create-invoice', 'lenal\catalog\Controllers\CommonController@createOrderInvoice');
        Route::post('/create/paypal', 'lenal\catalog\Controllers\CommonController@createOrderPaypal');
        Route::get('/get/{id}', 'lenal\catalog\Controllers\CommonController@getOrder');
        Route::get('/token/{token}', 'lenal\catalog\Controllers\CommonController@getOrderByToken');
    });

    Route::get('/diamonds-similar/{id}', 'lenal\catalog\Controllers\DiamondsController@similarItems');
    Route::get('/weddings-more-metals/{id}', 'lenal\catalog\Controllers\WeddingsController@moreMetalsSlider');
    Route::get('/weddings-similar-collections/{id}', 'lenal\catalog\Controllers\WeddingsController@similarCollectionsSlider');
    Route::get('/engagement-rings-for-diamond', 'lenal\catalog\Controllers\EngagementsController@engagementRingsForDiamondFeed');

    Route::prefix('constructor')->group(function(){
        Route::post('/filter-rings/{id}', 'lenal\catalog\Controllers\ConstructorController@getSuitableRings');
        Route::post('/filter-diamonds/{id}', 'lenal\catalog\Controllers\ConstructorController@getSuitableDiamonds');
        Route::post('/match-products', 'lenal\catalog\Controllers\ConstructorController@matchProducts');
        Route::prefix('complete-rings')->group(function(){
            Route::post('', 'lenal\catalog\Controllers\ConstructorController@saveCompleteRing');
            Route::get('', 'lenal\catalog\Controllers\ConstructorController@getCompleteRings');
            Route::post('/delete', 'lenal\catalog\Controllers\ConstructorController@deleteCompleteRing');
            Route::put('', 'lenal\catalog\Controllers\ConstructorController@updateCompleteRing');
        });
        Route::post('/add-to-cart', 'lenal\catalog\Controllers\ConstructorController@addToCart');
    });

    Route::get('/your-picks/{type}', 'lenal\catalog\Controllers\CommonController@getUserPicks');
    Route::get('/your-picks', 'lenal\catalog\Controllers\CommonController@getUserAllPicks');

    Route::get('/viewed/{type}', 'lenal\catalog\Controllers\CommonController@getViewedItems');

    Route::get('/share-list/{id}', 'lenal\catalog\Controllers\CommonController@getShareList');

    Route::get('/homepage-suggest', 'lenal\catalog\Controllers\CommonController@getSuggestsHomepage');

    Route::post('/product-send-hint', 'lenal\catalog\Controllers\CommonController@sendProductHint');

    Route::prefix('share-complete-rings')->group(function() {
        Route::post('/link', 'lenal\catalog\Controllers\CommonController@shareCompleteRings');
        Route::post('/mail', 'lenal\catalog\Controllers\CommonController@shareCompleteRingsMail');
        Route::get('/list/{id}', 'lenal\catalog\Controllers\CommonController@shareCompleteRingsList');
    });

    Route::get('/filter-videos', 'lenal\catalog\Controllers\CommonController@getFilterDescriptions');

    Route::prefix('payment')->group(function() {
        Route::get('/paysystems', 'lenal\catalog\Controllers\PaymentController@paySystemsList');
        Route::post('/proceed', 'lenal\catalog\Controllers\PaymentController@proceedPayment');
        Route::post('/paypal', 'lenal\catalog\Controllers\PaymentController@paypal');
        Route::post('/adyen', 'lenal\catalog\Controllers\PaymentController@adyen');
        Route::any('/alipay', 'lenal\catalog\Controllers\PaymentController@alipay');
        Route::any('/adyen/notification', 'lenal\catalog\Controllers\PaymentController@adyenNotification')->name('adуen.notify');

        //Route::get('/paypal', 'lenal\catalog\Controllers\PaymentController@paypalTest'); // test TODO remove
    });

    Route::prefix('invoice')->group(function() {
        Route::get('{alias}', 'lenal\catalog\Controllers\InvoicesController@invoice');
    });

    Route::prefix('payment-notify')->group(function() {
        Route::get('/paypal', 'lenal\catalog\Controllers\PaymentController@paypalNotify')
            ->name('paypal_notify');
    });

    Route::get('/homepage-wedding-anniversary', 'lenal\catalog\Controllers\CommonController@homepage360Sliders')
        ->name('homepage-wedding-anniversary');

    Route::get('/recommend-diamonds/{id}', 'lenal\catalog\Controllers\DiamondsController@recommendDiamonds');

    Route::prefix('promocode')
        ->namespace('lenal\catalog\Controllers')
        ->group(function() {
            Route::post('apply', 'PromocodesController@apply');
            Route::post('confirm', 'PromocodesController@confirmCode');
            Route::delete('remove', 'PromocodesController@remove');
        });
});

Route::get('feed/download', 'lenal\catalog\Controllers\FeedController@download');