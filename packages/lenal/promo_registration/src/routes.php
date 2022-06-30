<?php

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::prefix('api/promo_registration')
    ->middleware(['qcookie', StartSession::class])
    ->namespace('lenal\promo_registration\Controllers')
    ->group(function () {
        Route::get('content', 'MainController@content');
        Route::post('register', 'MainController@register');
    });
