<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;
use R64\NovaFields\JSON;

class Paysystem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Paysystem';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'slug', 'name'
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
            Text::make('Name'),
            Select::make('Type')
                ->options([
                    'credit_card' => 'Credit card',
                    'other' => 'Other'
                ])
                ->displayUsingLabels(),
            Translatable::make('Description'),
            Image::make('Logo')->disk(config('filesystems.cloud')),
            JSON::make('Credentials', $this->paysystemCredentials($this->slug))
        ];
    }

    private function paysystemCredentials($paysystem = null)
    {
        switch ($paysystem) {
            case 'bank_transfer':
                return [
                    Text::make('Bank'),
                    Text::make('Bank Branch address'),
                    Text::make('Beneficiary name'),
                    Text::make('Beneficiary address'),
                    Text::make('Account number'),
                    Text::make('BSB'),
                    Text::make('SWIFT code'),
                    Text::make('Payment discount'),
                ];
            case 'alipay':
                return [
                    Text::make('Merchant ID', 'merchant_id'),
                    Text::make('Authentication code', 'authentication_code'),
                ];
            default:
                return [
                    Text::make('Token'),
                    Text::make('Secret')
                ];
        }
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

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public static function label()
    {
        return 'Payment methods';
    }
}
