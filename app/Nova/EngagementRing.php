<?php

namespace App\Nova;

use App\Nova\Actions\AttachOffer;
use App\Nova\Actions\DetachOffer;
use App\Nova\Filters\EngagementCollection;
use App\Nova\Filters\EngagementMetal;
use App\Nova\Filters\EngagementShape;
use App\Nova\Filters\EngagementStyle;
use App\Nova\Filters\OffersFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;

class EngagementRing extends Resource
{
    use CustomFieldElements;
    use TabsOnEdit;


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Rings\EngagementRing';

    public function title()
    {
        return $this->item_name.' '
            .$this->stoneShape->title.' '
            .$this->ringStyle->title.' '
            .$this->metal->title.' '
            .$this->setting_type.' '
            .$this->stone_size;
    }

    public function subtitle()
    {
        return $this->ringCollection->title;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'sku', 'item_name'
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Engagement rings';
    }

    public static function usesScout()
    {
        return false;
    }

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Rings';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Engagement ring', [
                Tab::make('General', [
                    ID::make()->sortable(),
                    Translatable::make('Item name', 'item_name')
                        ->rules('required')
                        ->hideFromIndex(),
                    Text::make('Group sku', 'group_sku')->help(trans('nova.common.sku-group')),
                    Translatable::make('Header', 'header')->hideFromIndex(),
                    Translatable::make('Sub header', 'sub_header')->hideFromIndex(),
                    Text::make('Slug', 'slug')
                        ->rules('required')
                        ->creationRules('unique:engagement_rings,slug')
                        ->updateRules('unique:engagement_rings,slug,{{resourceId}}')
                        ->hideFromIndex(),
                    Text::make('SKU', 'sku')
                        ->rules('required')
                        ->creationRules('unique:engagement_rings,sku')
                        ->updateRules('unique:engagement_rings,sku,{{resourceId}}'),
                    Text::make('excl GST', 'raw_price')
                        ->rules('numeric')
                        ->sortable(),
                    Text::make('inc GSD', 'inc_price')
                        ->rules('numeric')
                        ->hideFromIndex(),
                    Number::make('Discount price', 'discount_price')
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ])
                        ->sortable(),
                    Translatable::make('Description', 'description')
                        ->rules('max:1000')
                        ->hideFromIndex(),

                    Text::make('Delivery period (weeks)', 'delivery_period')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),
                    Text::make('Delivery period (days)', 'delivery_period_days')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),

                    Boolean::make('Disable constructor', 'disable_constructor')->hideFromIndex(),
                    Boolean::make('In store', 'in_store')->hideFromIndex(),
                    Boolean::make('Is top', 'is_top')->hideFromIndex(),
                    Boolean::make('The Best for Merchant', 'is_best_for_merchant')->hideFromIndex(),
                    Number::make('Custom Sort Field', 'custom_sort')
                        ->rules(['required', 'integer'])
                        ->default(0)
                        ->sortable(),
                ]),
                Tab::make(trans('Images'), [
                    Images::make('Images', 'img-engagement')
                        ->conversionOnIndexView('jpg-225x225')
                        ->conversionOnDetailView('jpg-225x225')
                        ->conversionOnForm('jpg-225x225')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->setFileName(function($originalFilename, $extension, $model){
                            $info = pathinfo($originalFilename);
                            $originalFilename =  basename($originalFilename,'.'.$info['extension']);
                            return Str::slug(basename($originalFilename)) . '.' . $extension;
                        }),

                    Images::make('Images Old', 'engagement-images')
                        ->conversionOnIndexView('thumb')
                        ->conversionOnDetailView('thumb')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->help('Don\'t attache new images'),
                ]),
                Tab::make(trans('Video'), [
                    Files::make('Video', 'engagement-video'),
                ]),
                Tab::make(trans('Images 3D'), [
                    Images::make('Images 360', 'engagement-images-3d'),
                ]),
                Tab::make('Properties', array_merge([
                    BelongsTo::make('Metal', 'metal'),
                    BelongsTo::make('Ring collection', 'ringCollection'),
                    BelongsTo::make('Ring style', 'ringStyle', 'App\Nova\EngagementRingStyle')->nullable(),
                    Boolean::make('Active', 'is_active')->default(true),
                    Boolean::make('Has New Renders', 'has_new_renders')->default(false),
                    Select::make('Gender', 'gender')->options([
                        'n' => 'Neutral',
                        'f' => 'Female',
                        'm' => 'Male'
                    ])->default('f')->required(),
                    Text::make('Band width', 'band_width')
                        ->rules('numeric', 'max:15'),
                    Number::make('Min stone carat for setting', 'min_stone_carat')
                        ->step(0.001)
                        ->hideFromIndex(),
                    Number::make('Max stone carat for setting', 'max_stone_carat')
                        ->step(0.001)
                        ->hideFromIndex(),
                    BelongsTo::make('Min ring size', 'minRingSize', 'App\Nova\RingSize')
                        ->hideFromIndex(),
                    BelongsTo::make('Max ring size', 'maxRingSize', 'App\Nova\RingSize')
                        ->hideFromIndex(),
                    BelongsTo::make('Stone shape', 'stoneShape', 'App\Nova\Shape'),
                    Text::make('Stone size', 'stone_size')
                        ->rules('numeric')
                        ->help('set dimension in \'ct\''),
                    Select::make('Setting type', 'setting_type')
                        ->options([
                            '2 Claw'    => '2 Claw',
                            '3 Claw'    => '3 Claw',
                            '4 Claw'    => '4 Claw',
                            '5 Claw'    => '5 Claw',
                            '6 Claw'    => '6 Claw',
                            '8 Claw'    => '8 Claw',
                            '9 Claw'    => '9 Claw',
                            'Bezel set' => 'Bezel set',
                        ])
                        ->rules('required')
                        ->hideFromIndex(),
                    Text::make('Side setting type', 'side_setting_type')
                        ->hideFromIndex(),
                    Text::make('Approx Carat Weight', 'carat_weight')
                        ->hideFromIndex(),
                    Text::make('Average Side Stone Colour', 'average_ss_colour')
                        ->hideFromIndex(),
                    Text::make('Average Side Stone Clarity', 'average_ss_clarity')
                        ->hideFromIndex(),
                    Number::make('Approx stones', 'approx_stones')
                        ->rules('max:1000'),
                ], $this->collectCustomFields(\lenal\catalog\Models\Products\Category::ENGAGEMENT))),
                Tab::make('Offers', [
                    MorphToMany::make('Offers')
                        ->hideFromIndex()
                ]),
            ])->withToolbar()
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
            new EngagementMetal,
            new EngagementStyle,
            new EngagementShape,
            new EngagementCollection,
            new OffersFilter,
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
        return [
            new AttachOffer,
            new DetachOffer,
        ];
    }
}
