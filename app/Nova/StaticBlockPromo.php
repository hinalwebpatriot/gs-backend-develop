<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class StaticBlockPromo extends Resource
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
            HiddenField::make('Block type')->defaultValue('promo')->hideFromIndex()->hideFromDetail(),

            Images::make('Banner', 'promo')
                ->customPropertiesFields([
                    Select::make('Language')->options(config('translatable.locales')),
                ])
                ->help('Hover and click edit button to set file language')
                ->rules('required'),
            File::make('Undercover Video', 'undercover_video')
                ->rules('mimes:mp4,mov,wmv,flv,avi,avchd,webm,mkv')
                ->path('promo')
                ->disk(config('filesystems.cloud')),
            Translatable::make('Text')->trix(),
            Translatable::make('Link', 'link')->rules('required')->singleLine(),
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
        return 'Promo blocks';
    }

    public static function singularLabel()
    {
        return 'Promo block';
    }

    public static $displayInNavigation = false;

    public static $category = 'Static content';


}
