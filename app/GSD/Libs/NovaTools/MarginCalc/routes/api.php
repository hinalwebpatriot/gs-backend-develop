<?php

use GSD\Libs\NovaTools\MarginCalc\Http\Controllers\MarginCalcController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/index', [MarginCalcController::class, 'index']);
Route::post('/margin', [MarginCalcController::class, 'marginPercent']);
