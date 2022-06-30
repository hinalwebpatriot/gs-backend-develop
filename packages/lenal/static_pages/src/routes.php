<?php

Route::prefix('api/static')->group(function (){
    Route::get('get-page/{code}', 'lenal\static_pages\Controllers\StaticPagesController@page');
});
