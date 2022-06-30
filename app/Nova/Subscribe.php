<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Silvanite\NovaFieldCheckboxes\Checkboxes;

class Subscribe extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\subscribe\Models\Subscribe';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Subscribes';

    public static function label()
    {
        return 'Subscribers';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'email',
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
            Text::make('E-mail', 'email')
                ->rules('required', 'email'),
            Checkboxes::make('Type')
                ->options([
                    'new_collection' => 'New collection',
                    'sale' => 'Sale',
                    'discounts' => 'Discounts'
                ])
                ->rules('required'),
            Select::make('Gender')
                ->options([
                    'man' => 'Man',
                    'woman' => 'Woman'
                ])
                ->rules('required'),
            DateTime::make('Date', 'created_at')->exceptOnForms()
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
        return [
            new DownloadExcel(),
        ];
    }
}
