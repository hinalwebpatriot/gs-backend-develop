<?php
Route::prefix('api')->group(function(){
    Route::post('api/sitemaps', 'lenal\sitemap\Controllers\SitemapController@index');
});