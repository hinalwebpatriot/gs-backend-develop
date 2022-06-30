<?php
Route::prefix('api/subscribe')->group(function () {
    Route::post('save', 'lenal\subscribe\Controllers\SubscribeController@save');
    Route::get('get-form', 'lenal\subscribe\Controllers\SubscribeController@getSubscribeForm');
});
