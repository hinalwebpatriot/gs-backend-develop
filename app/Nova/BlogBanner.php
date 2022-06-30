<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogBanner extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\banners\Models\Banner';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    public static $globallySearchable = false;
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            //ID::make()->sortable(),
            Image::make('Image', 'image')->disk(config('filesystems.cloud')),
            /*Select::make('Page')
                ->options([
                    'blog' => 'blog',
                    'other' => 'other'
                ])*/
                //->onlyOnForms()
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

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Blog';

    public static function label() {
        return 'Banner';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->firstOrCreate(['page'=> 'blog']);
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }

    public static $displayInNavigation = false;
}
