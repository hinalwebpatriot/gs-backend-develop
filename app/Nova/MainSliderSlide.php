<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;
use Timothyasp\Color\Color;
use Waynestate\Nova\CKEditor;

class MainSliderSlide extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\MainSlider\Models\MainSliderSlide';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Main slider';

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return trans('nova.main_slider.sidebar_title.main_slider_slide');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Main slider', [
                Tab::make(trans('nova.main_slider.tabs.general'), [
                    ID::make()->sortable(),
                    Text::make(trans('nova.main_slider.title'), 'title')
                        ->rules('required', 'max:10'),
                    CKEditor::make(trans('nova.main_slider.slider_text'), 'slider_text')
                        ->hideFromIndex()->stacked(),
                    Number::make(trans('nova.main_slider.sort'), 'sort')
                        ->rules('required', 'min:0'),
                ]),

                Tab::make(trans('nova.main_slider.tabs.preview'), [
                    Image::make(trans('nova.main_slider.image'), 'image')
                        ->disk(config('filesystems.cloud'))
                        ->rules('max:2048'),
                    File::make(trans('nova.main_slider.undercover'), 'undercover')
                        ->rules('mimes:gif')
                        ->disk(config('filesystems.cloud'))
                        ->hideFromIndex(),
                    File::make(trans('nova.main_slider.undercover_video'), 'undercover_video')
                        ->rules('mimes:mp4,mov,wmv,flv,avi,avchd,webm,mkv')
                        ->disk(config('filesystems.cloud'))
                        ->hideFromIndex(),
                    Text::make(trans('nova.main_slider.youtube_code'), 'youtube_code')->hideFromIndex(),
                    Translatable::make('Alt', 'alt')->hideFromIndex(),
                    Color::make(trans('nova.main_slider.bg_color'), 'bg_color')
                        ->rules('max:10'),
                ]),

                Tab::make(trans('nova.main_slider.tabs.buttons'), [
                    Translatable::make(
                        trans('nova.main_slider.browse_button_title'),
                        'browse_button_title'
                    )->hideFromIndex(),
                    Translatable::make(
                        trans('nova.main_slider.browse_button_link'),
                        'browse_button_link'
                    )->hideFromIndex(),
                    Translatable::make(trans(
                        'nova.main_slider.detail_button_title'),
                        'detail_button_title'
                    )->hideFromIndex(),
                    Translatable::make(
                        trans('nova.main_slider.detail_button_link'),
                        'detail_button_link'
                    )->hideFromIndex(),
                ]),
            ])->withToolbar()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
