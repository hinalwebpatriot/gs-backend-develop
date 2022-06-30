<?php
Route::prefix('api/blocks')->group(function (){
    Route::get('certificate/{page}', 'lenal\blocks\Controllers\BlocksController@certificate');
    Route::get('guide/{page}', 'lenal\blocks\Controllers\BlocksController@guide');
    Route::get('why-diamonds', 'lenal\blocks\Controllers\BlocksController@diamondsDescription');
    Route::get('promo/{page}', 'lenal\blocks\Controllers\BlocksController@promo');
    Route::get('additional-info/{page}', 'lenal\blocks\Controllers\BlocksController@additionalInfo');
    Route::get('slider/{page}', 'lenal\blocks\Controllers\BlocksController@slider');
    Route::get('contacts-page', 'lenal\blocks\Controllers\BlocksController@contactsPage');
    Route::get('recommend-products/{page}', 'lenal\blocks\Controllers\BlocksController@recommendProducts');
    Route::get('complete-look', 'lenal\blocks\Controllers\BlocksController@completeLook');
    Route::get('occasion-slider', 'lenal\blocks\Controllers\BlocksController@occasionSlider');
    Route::get('second-rings-slider', 'lenal\blocks\Controllers\BlocksController@secondRingsSlider');
    Route::get('top-picks/{page}', 'lenal\blocks\Controllers\BlocksController@topPicks');
    Route::get('story-custom-jewelry', 'lenal\blocks\Controllers\BlocksController@storyCustomJewelry');
});
