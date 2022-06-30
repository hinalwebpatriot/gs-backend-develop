<?php

Route::prefix('api/reviews')->group(function (){
    Route::post('add', 'lenal\reviews\Controllers\ReviewsController@addReview');
    Route::get('site', 'lenal\reviews\Controllers\ReviewsController@getSiteReviews');
    Route::get('products', 'lenal\reviews\Controllers\ReviewsController@getProductsReviews');
    Route::get('diamonds/{id?}', 'lenal\reviews\Controllers\ReviewsController@getDiamondReviews');
    Route::get('engagement-rings/{id?}', 'lenal\reviews\Controllers\ReviewsController@getEngagementsReviews');
    Route::get('wedding-rings/{id?}', 'lenal\reviews\Controllers\ReviewsController@getWeddingsReviews');
    Route::get('catalog-products/{id?}', 'lenal\reviews\Controllers\ReviewsController@getCatalogProductReviews');
});
