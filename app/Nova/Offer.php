<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use lenal\offers\Rules\OneSaleOffer;
use MrMonat\Translatable\Translatable;

class Offer extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\offers\Models\Offer';

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Rings';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * @return string
     */
    public static function label()
    {
        return 'Offers';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Offers', [
                Tab::make(trans('General'), [
                    ID::make()->sortable(),
                    Boolean::make('Is active', 'enabled'),
                    Translatable::make('Title')
                        ->rules('required'),
                    Text::make('Slug')
                        ->rules('required')
                        ->creationRules('unique:offers,slug')
                        ->updateRules('unique:offers,slug,{{resourceId}}'),
                    Number::make('Order', 'sort')
                        ->rules('required')
                        ->sortable(),
                ]),
                Tab::make(trans('Description'), [
                    Translatable::make('Description')
                        ->hideFromIndex()
                        ->asHtml()
                        ->trix(),
                ]),
                Tab::make(trans('Engagement Ring Collections'), [
                    BelongsToMany::make('Collection', 'collections', RingCollection::class)
                ]),
                Tab::make(trans('Is sale'), [
                    Boolean::make('Is sale', 'is_sale')
                        ->rules([
                            new OneSaleOffer($request->resourceId),
                        ]),

                    // Gender dependencies
                    NovaDependencyContainer::make([
                        Number::make('Discount')
                            ->min(1)
                            ->max(80)
                            ->step(0.01)
                            ->rules('required_with:is_sale|number'),
                    ])->dependsOn('is_sale', true),
                ])
            ])->withToolbar()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
