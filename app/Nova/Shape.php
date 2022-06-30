<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use MrMonat\Translatable\Translatable;

class Shape extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Diamonds\Shape';

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Diamond properties';

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Translatable::make('Title', 'title')
                ->rules('required'),
            Translatable::make('Alt', 'alt')
                ->rules('required'),
            Image::make('Preview image', 'preview_image')
                ->disk(config('filesystems.cloud'))
                ->help('this image will be used on diamonds feed page'),
            //HasMany::make('Diamonds'),
            //HasMany::make('Engagement rings', 'engagementRings'),
            Files::make('Banners', 'banner')
                ->customPropertiesFields([
                    Select::make('Language')->options(config('translatable.locales')),
                ])
                ->help('Click edit button to set file language'),
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
