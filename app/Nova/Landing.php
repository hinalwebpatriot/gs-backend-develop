<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use HasManySelectField\HasManySelectField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class Landing extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\landings\Models\Landing';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'Landings';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'slug',
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
            Tabs::make('Landing', [
                Tab::make(trans('nova.general'), [
                    ID::make()->sortable(),
                    Translatable::make('Header', 'header')->singleLine()->rules('required'),
                    Text::make('Slug', 'slug')
                        ->rules([
                            'required',
                            Rule::unique('landings')->ignore((int)$request->route()->parameter('resourceId'))
                        ]),

                    Images::make(trans('nova.landing-image'), \lenal\landings\Models\Landing::MEDIA_COLLECTION)
                        ->singleImageRules('dimensions:min_width=400')
                        ->croppable(),

                    Translatable::make('Meta title', 'meta_title')->singleLine()->hideFromIndex(),
                    Translatable::make('Meta description', 'meta_description')->hideFromIndex(),
                    Translatable::make('Meta keywords', 'meta_keywords')->hideFromIndex()
                ]),

                Tab::make(trans('nova.landing-rings'), [
                    HasManySelectField::make('')
                        ->setResourceModel('\lenal\landings\Models\Landing')
                        ->setResourceItemRelated('rings')
                        ->setItemModel('\lenal\catalog\Models\Rings\EngagementRing')
                        ->setResourceFormat('\lenal\landings\Resources\AdminLandingRingResource')
                        ->setSearchColumn('sku', 'Engagement ring SKU')
                ]),

                Tab::make(trans('nova.landing-diamonds'), [
                    HasManySelectField::make('')
                        ->setResourceModel('\lenal\landings\Models\Landing')
                        ->setResourceItemRelated('diamonds')
                        ->setItemModel('\lenal\catalog\Models\Diamonds\Diamond')
                        ->setResourceFormat('\lenal\landings\Resources\AdminLandingDiamondResource')
                        ->setSearchColumn('sku', 'Diamond SKU')
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
