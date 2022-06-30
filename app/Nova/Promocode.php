<?php

namespace App\Nova;

use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\{Boolean, DateTime, ID, Number, Select, Text};
use lenal\catalog\Models\Promocode as PromocodeModel;
use lenal\catalog\Models\Rings\RingCollection;
use R64\NovaFields\Textarea;
use Silvanite\NovaFieldCheckboxes\Checkboxes;

class Promocode extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Promocode';

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
    public static $search   = [
        'id',
        'code',
        'personal_email',
    ];
    public static $category = 'Catalog';

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
            Text::make('Code', 'code')
                ->help(trans('nova.if-empty-code-auto-generate'))
                ->rules(['nullable', 'unique:promocodes,code,{{resourceId}},']),
            Number::make('Discount (%)', 'discount')
                ->min(0)
                ->max(80)
                ->step(0.01)
                ->rules(['required', 'numeric']),

            Number::make('Discount (AUD)', 'discount_value')
                ->min(0)
                ->rules('nullable', 'numeric', 'min:0'),

            Select::make('Kind', 'kind')
                ->options(PromocodeModel::kinds())
                ->rules('required'),

            //Personal promo kind fields
            NovaDependencyContainer::make([
                Text::make('E-mail', 'personal_email')
                    ->rules('required', 'email', 'max:64'),

                Text::make('Confirm code', 'confirm_code')
                    ->withMeta([
                        'extraAttributes' => [
                            'readonly' => true,
                        ]
                    ])
                    ->help(trans('nova.confirm-code-help'))
                    ->hideFromIndex(),
            ])->dependsOn('kind', PromocodeModel::KIND_PERSONAL),

            Textarea::make('Products SKU', 'products_sku')
                ->rules('max:512')
                ->help(trans('nova.sku-list-with-separate-comma'))
                ->hideFromIndex(),

            Checkboxes::make('Category', 'category')
                ->options(\lenal\catalog\Models\Products\Category::promocodeCategories())
                ->withoutTypeCasting(),

            Checkboxes::make('Ring collections', 'collections')
                ->options(RingCollection::query()->get()->pluck('title', 'id')->toArray())
                ->withoutTypeCasting(),

            /*Checkboxes::make('Jewellery collections', 'brands')
                ->options(\lenal\catalog\Models\Products\Brand::retrieveActiveBrands())
                ->withoutTypeCasting(),*/

            Text::make('Max number of uses', 'max_times')
                ->rules(['integer', 'min:0'])
                ->help(trans('nova.zero-means-unlimited'))
                ->hideFromIndex(),

            Text::make('Used count', 'used_times')
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                    ]
                ])
                ->hideFromIndex(),
            DateTime::make('Validity until', 'validity_date'),
            Boolean::make('Is active', 'is_active'),
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
        return 'Promocodes';
    }
}
