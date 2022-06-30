<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class Article extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\blog\Models\Article';

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
        'title',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return trans('nova.article.sidebar_title');
    }

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Blog';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Category', 'category', 'App\Nova\BlogCategory'),
            Translatable::make('Title')->rules('required')->sortable(),
            Text::make('Url', 'slug')->hideFromIndex()->rules('required'),
            Translatable::make('Preview text')->hideFromIndex(),
            HasMany::make('Detail text', 'detailText', 'App\Nova\ArticleDetailBlock'),
            Images::make('Main image', 'main_image'),
            Number::make('Priority')
                ->min(0)
                ->withMeta($this->priority ? [] : ["value" => 0])
                ->help('Articles with lower priority would be display first')
                ->rules('required')
                ->sortable(),
            Date::make('Last updated', 'updated_at')->exceptOnForms()->sortable(),
            HiddenField::make('Views', 'view_count')->defaultValue(0)->sortable()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

}
