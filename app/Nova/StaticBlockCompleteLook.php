<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class StaticBlockCompleteLook extends Resource
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
            HiddenField::make('Block type')
                ->defaultValue('complete-look')->hideFromIndex()->hideFromDetail(),

            Translatable::make('Title')->rules('required')->singleLine(),
            Images::make('Image', 'complete_look')
                ->customPropertiesFields([
                    Select::make('Language')
                        ->options(config('translatable.locales'))
                        ->rules('required'),
                ])
                ->help('Hover and click edit button to set file language'),
            Translatable::make('Video', 'video_link')->singleLine(),
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
        return 'Text blocks';
    }

    public static function singularLabel()
    {
        return 'block';
    }

    public static $displayInNavigation = false;

    public static $category = 'Static content';

}
