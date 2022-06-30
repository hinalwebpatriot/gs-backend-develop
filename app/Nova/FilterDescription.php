<?php

namespace App\Nova;

use App\Nova\Filters\FilterFeed;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class FilterDescription extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\FilterDescription';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'slug';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'slug',
        'product_feed',
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
            Text::make('Filter property slug' ,'slug')
                ->rules('required'),
            Select::make('Product type', 'product_feed')
                ->options([
                    'diamonds'=> 'Diamonds',
                    'engagement-rings' => 'Engagement rings',
                    'wedding-rings' => 'Wedding Rings'
                ])
                ->rules('required')
                ->displayUsingLabels(),
            Translatable::make('Video link')
                ->rules('required')
                ->singleLine()
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
        return [
            new FilterFeed
        ];
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


    public static $category = 'Additional content';

    public static function label()
    {
        return 'Filter descriptions';
    }

}
