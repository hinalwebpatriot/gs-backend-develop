<?php

use Illuminate\Support\Facades\Route;

Route::prefix('nova-vendor/has-many-select')
    ->namespace('HasManySelectField\Controllers')
    ->group(function() {
        Route::get('params', 'ParamsController@index');
        Route::get('items', 'ItemsController@index');
        Route::delete('detach', 'ItemsController@detach');
        Route::post('attach', 'ItemsController@attach');
        Route::get('search', 'ItemsController@search');
    });