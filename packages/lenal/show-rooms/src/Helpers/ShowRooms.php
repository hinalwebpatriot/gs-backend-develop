<?php

namespace lenal\ShowRooms\Helpers;

use lenal\ShowRooms\Models\ShowRoom;
use lenal\ShowRooms\Models\ShowRoomCountry;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Helpers
 */
class ShowRooms
{
    /**
     * @return string|null
     */
    public function getShowRoomsData()
    {
        return ShowRoom::query()->where('is_active', 1)->get();
    }

    /**
     * @return string|null
     */
    public function getShowRoomsCountryData()
    {
        return ShowRoomCountry::with([
            'showRooms' => function($builder) {
                $builder->where('is_active', 1);
            }
        ])->get();
    }

    /**
     * @param string $country_code
     *
     * @return string|null
     */
    public function getShowRoomsCountryDataByCode(string $country_code)
    {
        return ShowRoomCountry::with([
                'showRooms' => function($builder) {
                    $builder->where('is_active', 1);
                }
            ])
            ->where('code', $country_code)
            ->first();
    }
}
