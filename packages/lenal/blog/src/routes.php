<?php

Route::prefix('api/blog')->group(function (){
    Route::get('categories', 'lenal\blog\Controllers\BlogController@categories');
    Route::get('article/{slug}', 'lenal\blog\Controllers\BlogController@article');
    Route::get('related/{slug}', 'lenal\blog\Controllers\BlogController@related');
    Route::get('top-articles', 'lenal\blog\Controllers\BlogController@topArticles');
    Route::get('list/{slug?}', 'lenal\blog\Controllers\BlogController@list');
});

