<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 2/1/19
 * Time: 5:44 PM
 */

namespace App\Nova;


use Laravel\Nova\Http\Requests\NovaRequest;

class MainSliderSliderMobile extends MainSliderSlider
{
    public static function label()
    {
        return trans('nova.main_slider.sidebar_title.main_slider_mobile');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->firstOrCreate([
            'title' => 'default-mobile',
        ]);
    }
}