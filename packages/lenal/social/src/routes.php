<?php
Route::prefix('api/social')->group(function (){
    Route::get('get-support-contacts', 'lenal\social\Controllers\SocialController@getSupportContacts');
});
