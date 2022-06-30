<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class Brand extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Products\Brand';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';
    public static $category = 'Catalog';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Brands', [
                Tab::make('Brand info', [
                    ID::make()->sortable(),

                    Translatable::make('Title')->rules('required'),

                    Translatable::make('Description')->hideFromIndex(),

                    Text::make('Slug', 'slug')->rules(['required'])
                        ->creationRules('unique:brands,slug')
                        ->updateRules('unique:brands,slug,{{resourceId}}')
                ]),

                Tab::make('Story for this brand', [
                    Translatable::make('Title', 'story_title')->hideFromIndex()->singleLine(),

                    Translatable::make('Video', 'story_video')->hideFromIndex()->singleLine(),

                    Translatable::make('Text', 'story_text')->hideFromIndex(),
                ])
            ])->withToolbar(),
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

    public static function label()
    {
        return 'Brands';
    }
}
