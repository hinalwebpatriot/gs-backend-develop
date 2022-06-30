<?php

Route::middleware('api')->group(function () {
    Route::resource('api/main_slider', 'lenal\MainSlider\Controllers\MainSliderController')->only('index');
});

Route::middleware('web')->group(function () {
    Route::resource('main_slider_web', 'lenal\MainSlider\Controllers\MainSliderController')
        ->only(['index', 'store']);
});

Route::middleware('api')->group(function () {
    Route::get('api/main_slider_mobile', 'lenal\MainSlider\Controllers\MainSliderController@mobileSlider');
});