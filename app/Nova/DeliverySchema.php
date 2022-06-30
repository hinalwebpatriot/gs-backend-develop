<?php

namespace App\Nova;

use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class DeliverySchema extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\DeliverySchema';
    public static $category = 'Catalog';

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
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Delivery times';
    }

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

            Select::make('Category', 'category_slug')
                ->options(\lenal\catalog\Models\Products\Category::availableCategories())
                ->rules('required'),

            NovaDependencyContainer::make([
                Boolean::make('With diamond', 'with_diamond'),
            ])->dependsOn('category_slug', \lenal\catalog\Models\Products\Category::ENGAGEMENT),

            BelongsTo::make('Metal', 'metal')->nullable(),
            /** Костыль для NovaDependencyContainer */
            BelongsTo::make('Ring Style', 'engagementRingStyle', EngagementRingStyle::class)
                ->onlyOnIndex()->hideFromIndex()->nullable(),

            NovaDependencyContainer::make([
                BelongsTo::make('Ring Style', 'engagementRingStyle', EngagementRingStyle::class)->nullable(),
            ])->dependsOn('category_slug', \lenal\catalog\Models\Products\Category::ENGAGEMENT),

            Text::make('Delivery period (days)', 'delivery_period')
                ->rules(['nullable', 'integer'])
                ->sortable(),

            Text::make('Delivery period (weeks)', 'delivery_period_wk')
                ->rules(['nullable', 'integer'])
                ->sortable(),

            Textarea::make('Product sku', 'product_sku')->hideFromIndex()
                ->help('with comma(,) separator')
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
}
