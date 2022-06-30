<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \lenal\MarginCalculateTool\Controllers\TranslationController;
use \lenal\MarginCalculateTool\Controllers\ManufacturerController;
use \lenal\MarginCalculateTool\Controllers\ColorController;
use \lenal\MarginCalculateTool\Controllers\ClarityController;
use \lenal\MarginCalculateTool\Controllers\MarginController;
use \lenal\MarginCalculateTool\Controllers\MarginSyncController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::resource('/lang', TranslationController::class)
    ->only('index');

Route::resource('/manufacturer', ManufacturerController::class)
    ->only('index', 'show');
Route::resource('/color', ColorController::class)
    ->only('index', 'show');
Route::resource('/clarity', ClarityController::class)
    ->only('index', 'show');

Route::resource('/margin/{manufacturer_id?}', MarginController::class)
    ->only('index', 'store');
Route::resource('/margin/sync/{manufacturer_id}', MarginSyncController::class)
    ->only('store');
Route::get('/params', '\lenal\MarginCalculateTool\Controllers\MarginController@params');
