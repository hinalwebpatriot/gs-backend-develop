<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class StaticBlockGuide extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\blocks\Models\StaticBlock';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'text';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            BelongsTo::make('Page', 'dynamic_page_block', 'App\Nova\DynamicPage')->exceptOnForms(),
            Translatable::make('Title')->rules('required')->singleLine(),
            Translatable::make('Video link', 'video_link')->rules('required')->singleLine(),
            //File::make('PDF', 'file')->prunable()->rules('mimes:pdf'),
            Files::make('PDF', 'guide-pdf')
                ->singleMediaRules('mimes:pdf')
                ->customPropertiesFields([
                    Select::make('Language')->options(config('translatable.locales')),
                ])
                ->help('Click edit button to set file language'),
            HiddenField::make('Block type')->defaultValue('guide')->hideFromIndex()->hideFromDetail()
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
        return 'Guide blocks';
    }

    public static function singularLabel()
    {
        return 'Guide block';
    }

    public static $displayInNavigation = false;

    public static $category = 'Static content';

    public static function uriKey()
    {
        return 'static-blocks-guide';
    }

}
