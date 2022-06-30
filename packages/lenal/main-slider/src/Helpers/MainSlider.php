<?php

namespace lenal\MainSlider\Helpers;

use Illuminate\Http\Response;
use lenal\MainSlider\Models\MainSlider as SliderModel;
use lenal\MainSlider\Resources\MainSliderResource;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Helpers
 */
class MainSlider
{
    /**
     * @return string|null
     */
    public function getMainSliderData() {
        $slider_data = SliderModel::first();

        return $slider_data ? $slider_data : null;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateMainSliderData(array $data) {
        try {
            SliderModel::updateOrCreate(
                ["id" => 1],
                [
                    "data" => json_encode($data),
                ]
            );

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getMobileSlider()
    {
        return SliderModel::where('title', 'default-mobile')->first();
    }
}
