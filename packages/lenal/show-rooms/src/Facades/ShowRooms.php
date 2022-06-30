<?php

namespace lenal\ShowRooms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Facades
 * @method static getShowRoomsData: mixed
 * @method static getShowRoomsCountryData: mixed
 * @method static getShowRoomsCountryDataByCode(string $country_code): mixed
 */
class ShowRooms extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'show_rooms';
    }
}
