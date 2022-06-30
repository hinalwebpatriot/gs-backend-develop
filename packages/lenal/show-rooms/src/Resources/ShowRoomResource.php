<?php

namespace lenal\ShowRooms\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use lenal\ShowRooms\Models\ShowRoom;

/**
 * Class MainSliderCollection
 *
 * @package lenal\MainSlider\Collections
 * @mixin ShowRoom
 */
class ShowRoomResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'main_title' => $this->main_geo_title,
            'title' => $this->geo_title,
            'description' => $this->description,
            'location' => [
                'lat' => $this->geo_position_lat,
                'lng' => $this->geo_position_lng,
            ],
            'address' => $this->address,
            'image' => Storage::disk(config('filesystems.cloud'))
                ->url($this->image),
            'youtube_link' => $this->youtube_link,
            'phone' => [
                'number' => $this->phone,
                'description' => $this->phone_description,
            ],
            'schedule' => $this->schedule,
            'expert' => [
                'title' => $this->expert_title ?: null,
                'text' => $this->expert_text ?: null,
                'list_1' => $this->expert_list_1 ?: null,
                'list_2' => $this->expert_list_2 ?: null,
                'list_3' => $this->expert_list_3 ?: null,
                'name' => $this->expert_name ?: null,
                'email' => $this->expert_email ?: null,
                'photo' => $this->expert_photo ? Storage::disk(config('filesystems.cloud'))
                    ->url($this->expert_photo) : null,
            ],
            'button_title' => $this->tab_title,
            'header' => [
                'start' => $this->desc_start,
                'middle' => $this->desc_middle,
                'end' => $this->desc_end,
            ]
        ];
    }
}