<?php

namespace lenal\ShowRooms;

use Illuminate\Support\ServiceProvider;
use lenal\ShowRooms\Helpers\ShowRooms;

class ShowRoomsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('show_rooms', ShowRooms::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
