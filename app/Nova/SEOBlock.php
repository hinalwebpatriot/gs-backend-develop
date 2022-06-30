<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;
use Waynestate\Nova\CKEditor;

class SEOBlock extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\seo\Models\SEOBlock';

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
        'page'
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
            Text::make('Page', 'page')->rules('required'),
            Text::make('Title', 'title')->rules('required'),
            CKEditor::make('Description')
                ->rules('required')
                ->stacked()
                ->hideFromIndex(),
            CKEditor::make('Short Description', 'short_description')
                ->rules('nullable')
                ->stacked()
                ->hideFromIndex(),
            Image::make('Image', 'image')->disk(config('filesystems.cloud')),
            MorphMany::make('Collapses FAQ', 'collapses', Collapse::class),
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
        return 'SEO blocks';
    }

    public static function singularLabel()
    {
        return 'SEO block';
    }

    public static $category = 'SEO';

    public static function uriKey()
    {
        return 'seo-blocks';
    }
}
