<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;

class CountryVat extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\PriceCalculate\Models\CountryVat';

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

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return trans('nova.country_vat.sidebar_title');
    }

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Diamond products';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Country::make(trans('nova.country_vat.country_code'), 'country_code')
                ->rules('required'),
            Number::make(trans('nova.country_vat.vat'), 'vat')
                ->rules('required', 'numeric', 'lt:100'),
            DateTime::make(trans('nova.country_vat.created_at'), 'created_at')
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                    ]
                ])
                ->onlyOnDetail(),
            DateTime::make(trans('nova.country_vat.updated_at'), 'updated_at')
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                    ]
                ])
                ->exceptOnForms(),
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

    public static $displayInNavigation = false;
}
