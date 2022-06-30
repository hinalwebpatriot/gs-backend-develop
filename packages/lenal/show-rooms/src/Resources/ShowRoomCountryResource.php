<?php

namespace lenal\ShowRooms\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 */
class ShowRoomCountryResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'country_code'  => $this->code,
            'country_title' => $this->title,
            'show_rooms'    => $this->showRooms
                ->map(function ($show_room) {
                    return new ShowRoomResource($show_room);
                }),
        ];
    }
}