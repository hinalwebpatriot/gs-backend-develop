<?php

namespace lenal\ShowRooms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use lenal\ShowRooms\Facades\ShowRooms;
use lenal\ShowRooms\Resources\ShowRoomCountryResource;

/**
 * Class MainSliderController
 *
 * @package lenal\MainSlider\Controllers\Api
 */
class ShowRoomsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $show_rooms = ShowRooms::getShowRoomsCountryData()
            ->map(function ($show_room) {
                return new ShowRoomCountryResource($show_room);
            });

        return response()
            ->json($show_rooms);
    }

    /**
     * @param string $country_code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $country_code)
    {
        $show_room = ShowRooms::getShowRoomsCountryDataByCode($country_code);

        if (is_null($show_room)) {
            return response()
                ->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()
            ->json(new ShowRoomCountryResource($show_room));
    }
}
