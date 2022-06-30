<?php

Route::prefix('api/search')->group(function (){
    Route::get('/', '\lenal\search\Controllers\SearchController@countSearch')
        ->name('count_search');
    Route::get('preview', '\lenal\search\Controllers\SearchController@previewSearch')
        ->name('quick_search');
    Route::get('/detail/{model}', '\lenal\search\Controllers\SearchController@detailSearch')
        ->name('quick_search_detail');
});
