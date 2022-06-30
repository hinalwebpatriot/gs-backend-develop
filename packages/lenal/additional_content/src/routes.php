<?php
Route::prefix('api')->group(function (){
    Route::get('menu-dropdown', 'lenal\additional_content\Controllers\AdditionalContentController@getMenuDropdown');
    Route::get('faq', 'lenal\additional_content\Controllers\AdditionalContentController@faq');
});
