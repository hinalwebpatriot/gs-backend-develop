<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class StaticBlockAdditionalIcons extends Resource
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
    public static $title = 'title';

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
            HiddenField::make('Block type')->defaultValue('additional-info-icons')->hideFromIndex()->hideFromDetail(),

            Translatable::make('Title')->rules('required')->singleLine(),
            Images::make('Icons', 'image')
                ->customPropertiesFields([
                    Translatable::make('Title')->rules('required')->singleLine(),
                    Translatable::make('Link', 'link')->rules('required')->singleLine()
                ])
                ->help('Hover and click edit button to set icon title and link')
            /*Image::make('Icon', 'image')->rules('required'),
            Translatable::make('Title')->rules('required')->singleLine()->rules('required'),
            Translatable::make('Link', 'link')->rules('required')->singleLine(),*/
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
        return 'Icons block';
    }

    public static function singularLabel()
    {
        return 'Icons block';
    }

    public static $displayInNavigation = false;

    public static $category = 'Static content';


}
