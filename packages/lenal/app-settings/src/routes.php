<?php
Route::prefix('api/settings')->group(function (){
    Route::get('locales', 'lenal\AppSettings\Controllers\AppSettingsController@locales');
    Route::get('currencies', 'lenal\AppSettings\Controllers\AppSettingsController@currencies');
    Route::get('locations', 'lenal\AppSettings\Controllers\AppSettingsController@locations');
    Route::get('locations-data', 'lenal\AppSettings\Controllers\AppSettingsController@locationsData');
    Route::post('select-locations', 'lenal\AppSettings\Controllers\AppSettingsController@locationsSelected');
});
