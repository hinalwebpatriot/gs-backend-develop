<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class SEOMeta extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\seo\Models\Meta';

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
        'page', 'sitemap_page_url', 'title'
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
            Text::make('Page')->rules('required'),
            Text::make('Link for sitemap', 'sitemap_page_url')
                ->help('If set empty that link hides in sitemap')
                ->hideFromIndex(),
            Translatable::make('Title')->singleLine(),
            Translatable::make('Description')->hideFromIndex(),
            Translatable::make('keywords')->singleLine()->hideFromIndex(),
            Translatable::make('H1', 'h1')->singleLine()->hideFromIndex(),
            Translatable::make('SEO Text', 'seo_text')->hideFromIndex(),
            DateTime::make('Created At', 'created_at')->exceptOnForms()->hideFromIndex(),
            DateTime::make('Updated At', 'updated_at')->exceptOnForms(),
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

    public static function label()
    {
        return 'Meta data';
    }

    public static function singularLabel()
    {
        return 'meta data';
    }

    public static $category = 'SEO';

    public static function uriKey()
    {
        return 'seo-meta';
    }
}
