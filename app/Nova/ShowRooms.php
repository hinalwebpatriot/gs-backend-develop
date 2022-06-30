<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class ShowRooms extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\ShowRooms\Models\ShowRoom';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'geo_title';

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Show rooms';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'main_geo_title', 'geo_title', 'address',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return trans('nova.show_rooms.sidebar_title');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $geo_position_lat = $this->geo_position_lat ?? 0;
        $geo_position_lng = $this->geo_position_lng ?? 0;

        return [
            Tabs::make('ShowRooms', [
                Tab::make(trans('nova.show_rooms.tabs.general'), [
                    ID::make()->sortable(),
                    Translatable::make(trans('nova.show_rooms.main_geo_title'), 'main_geo_title')
                        ->rules('required', 'max:255'),
                    Translatable::make(trans('nova.show_rooms.geo_title'), 'geo_title')
                        ->rules('required', 'max:255'),
                    Translatable::make(trans('nova.show_rooms.description'), 'description')
                        ->rules('max:3000')
                        ->hideFromIndex(),
                    Boolean::make('Is active', 'is_active'),
                ]),

                Tab::make(trans('nova.show_rooms.tabs.marker'), [
                    NovaGoogleMaps::make(trans('nova.show_rooms.geo_position'), 'geo_position')
                        ->setValue($geo_position_lat, $geo_position_lng)
                        ->hideFromIndex(),
                ]),

                Tab::make(trans('nova.show_rooms.tabs.preview'), [
                    Image::make(trans('nova.show_rooms.image'), 'image')
                        ->disk(config('filesystems.cloud')),
                    Translatable::make(trans('nova.show_rooms.youtube_link'), 'youtube_link')
                        ->hideFromIndex(),
                ]),

                Tab::make(trans('nova.show_rooms.tabs.contact_info'), [
                    Translatable::make(trans('nova.show_rooms.address'), 'address')
                        ->rules('required', 'max:765'),

                    Text::make(trans('nova.show_rooms.phone'), 'phone'),

                    Translatable::make(trans('nova.show_rooms.phone_description'), 'phone_description')
                        ->hideFromIndex(),

                    Translatable::make(trans('nova.show_rooms.schedule'), 'schedule')
                        ->hideFromIndex(),

                    Translatable::make(trans('nova.show_rooms.tab_title'), 'tab_title')
                        ->rules('nullable', 'max:255')
                        ->hideFromIndex(),

                    Translatable::make(trans('nova.show_rooms.desc_start'), 'desc_start')
                        ->rules('nullable', 'max:255')
                        ->hideFromIndex(),

                    Translatable::make(trans('nova.show_rooms.desc_middle'), 'desc_middle')
                        ->rules('nullable', 'max:255')
                        ->hideFromIndex(),

                    Translatable::make(trans('nova.show_rooms.desc_end'), 'desc_end')
                        ->rules('nullable', 'max:255')
                        ->hideFromIndex(),
                ]),

                Tab::make(trans('nova.show_rooms.tabs.country'), [
                    BelongsTo::make(
                        trans('nova.show_rooms.country'),
                        'country',
                        ShowRoomCountries::class
                    )
                        ->rules('required'),
                ]),

                Tab::make('Expert', [
                    Translatable::make('Title', 'expert_title')->singleLine()->hideFromIndex(),
                    Translatable::make('Description', 'expert_text')->hideFromIndex(),
                    Translatable::make('List item description', 'expert_list_1')->singleLine()->hideFromIndex(),
                    Translatable::make('List item description', 'expert_list_2')->singleLine()->hideFromIndex(),
                    Translatable::make('List item description', 'expert_list_3')->singleLine()->hideFromIndex(),
                    Translatable::make('Expert\'s name', 'expert_name')->singleLine()->hideFromIndex(),
                    Text::make('Expert\'s email', 'expert_email')->hideFromIndex(),
                    Image::make('Expert\'s photo', 'expert_photo')->disk(config('filesystems.cloud'))
                        ->hideFromIndex(),

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
