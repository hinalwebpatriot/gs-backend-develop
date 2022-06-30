<?php

namespace lenal\MainSlider;

use Illuminate\Support\ServiceProvider;
use lenal\MainSlider\Helpers\MainSlider;

class MainSliderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('main_slider', MainSlider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(dirname(__FILE__)) . '/routes.php');
        $this->loadMigrationsFrom(realpath(dirname(__FILE__)) . '/migrations');
    }
}
