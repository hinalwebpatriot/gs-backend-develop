<?php

Route::middleware('api')->group(function () {
    Route::resource('api/show-rooms', 'lenal\ShowRooms\Controllers\ShowRoomsController')->only('index', 'show');
});