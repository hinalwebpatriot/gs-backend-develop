<?php
Route::prefix('api/banners')->group(function (){
    Route::get('get', 'lenal\banners\Controllers\BannersController@getPageBanner');
});
