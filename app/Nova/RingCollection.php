<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class RingCollection extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Rings\RingCollection';

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
        'id', 'title', 'description',
    ];

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Rings';

    public static function label()
    {
        return 'Collections';
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
            Tabs::make('RingCollection', [
                Tab::make('Collection info', [
                    ID::make()->sortable(),
                    Translatable::make('Title')
                        ->rules('required'),
                    Translatable::make('Description')
                        ->hideFromIndex(),
                    Text::make('Slug')
                        ->rules('required'),
                    HasMany::make('Engagement rings', 'engagementRings'),
                    HasMany::make('Wedding rings', 'weddingRings'),
                ]),

                Tab::make('Story for this collection', [
                    Translatable::make('Title', 'story_title')
                        ->hideFromIndex()->singleLine(),
                    Translatable::make('Video', 'story_video')
                        ->hideFromIndex()->singleLine(),
                    Translatable::make('Text', 'story_text')
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
