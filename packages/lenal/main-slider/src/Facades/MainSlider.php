<?php

namespace lenal\MainSlider\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MainSlider
 *
 * @mixin \lenal\MainSlider\Helpers\MainSlider
 */
class MainSlider extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'main_slider';
    }
}
