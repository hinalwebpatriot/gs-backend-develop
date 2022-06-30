<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Manufacturer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Diamonds\Manufacturer';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Diamond properties';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'slug'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        if (request()->route()->parameter('resourceId') == 4) {
            //HasMany::make('Diamonds') making request with loading ALL records and we got error Out of memory
            // The error actual only for kiran manufacturer where over 80000 records
            ini_set('memory_limit', '-1');
        }

        return [
            ID::make()->sortable(),

            Text::make(trans('nova.manufacturer.title'), 'title')
                ->rules('required', 'max:255'),
            Text::make(trans('nova.manufacturer.slug'), 'slug')
                ->rules('required', 'max:255'),
            Boolean::make('Activate', 'is_active'),
            Boolean::make('Custom Made', 'custom_made')->help('If set true then for diamonds won\'t calculate margin'),
            HasMany::make('Diamonds'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static $indexDefaultOrder = ['id' => 'asc'];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
    }

}
