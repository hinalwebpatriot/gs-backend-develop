<?php
Route::prefix('api/seo')->group(function (){
    Route::get('blocks/{page}', 'lenal\seo\Controllers\SEOController@pageBlocks');
    Route::get('meta/{page}', 'lenal\seo\Controllers\SEOController@meta');
    Route::get('meta/catalog/{page}', 'lenal\seo\Controllers\SEOController@catalogMeta');
    Route::post('fetch-redirect', 'lenal\seo\Controllers\SEOController@fetchRedirectUrl');
});
