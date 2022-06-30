<?php

Route::prefix('api/auth')->group(function (){
    Route::post('register', 'lenal\auth\Controllers\AuthController@register');
    Route::get('verify/{id?}', 'lenal\auth\Controllers\AuthController@verify')
        ->name('verification.verify')
        ->middleware('signed');
    Route::get('resend', 'lenal\auth\Controllers\AuthController@resendVerify');
    Route::post('resend-verify-email', 'lenal\auth\Controllers\AuthController@resendVerifyEmail');
    Route::post('login', 'lenal\auth\Controllers\AuthController@login');
    Route::post('logout', 'lenal\auth\Controllers\AuthController@logout')->middleware('qcookie', 'auth:api');

    Route::get('userdata', 'lenal\auth\Controllers\AuthController@user')->middleware('auth:api', 'verified');
    Route::post('resetpassword', 'lenal\auth\Controllers\ForgotPasswordController@sendResetLinkEmail');
    Route::post('changepassword', 'lenal\auth\Controllers\ResetPasswordController@reset');
});

