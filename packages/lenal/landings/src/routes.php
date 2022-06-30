<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/landing')->middleware(['qcookie'])->group(function (){
    Route::get('{slug}', 'lenal\landings\Controllers\LandingsController@index');
});
