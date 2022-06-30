<?php

namespace App\Nova;

use App\Nova\Actions\AttachOffer;
use App\Nova\Actions\DetachOffer;
use App\Nova\Filters\OffersFilter;
use App\Nova\Filters\WeddingCollection;
use App\Nova\Filters\WeddingGender;
use App\Nova\Filters\WeddingMetal;
use App\Nova\Filters\WeddingStyle;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use lenal\catalog\Models\Rings\WeddingRingStyle;
use MrMonat\Translatable\Translatable;

class WeddingRing extends Resource
{
    use CustomFieldElements;
    use TabsOnEdit;


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Rings\WeddingRing';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */

    public function title()
    {
        return $this->sku . ' - ' . $this->item_name . ' ' . $this->metal->title;
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
        return 'Wedding rings';
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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $ring_styles = WeddingRingStyle::all();

        return [
            Tabs::make('Wedding Ring', [
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
                        ->creationRules('unique:wedding_rings,slug')
                        ->updateRules('unique:wedding_rings,slug,{{resourceId}}')
                        ->hideFromIndex(),
                    Text::make('SKU', 'sku')
                        ->rules('required')
                        ->creationRules('unique:wedding_rings,sku')
                        ->updateRules('unique:wedding_rings,sku,{{resourceId}}'),
                    Text::make('excl GST', 'raw_price')
                        ->rules('required', 'numeric')
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
                    Boolean::make('In store', 'in_store')->hideFromIndex(),
                    Boolean::make('Is top', 'is_top')->hideFromIndex(),
                    Number::make('Custom Sort Field', 'custom_sort')
                        ->rules(['required', 'integer'])
                        ->default(0)
                        ->sortable(),
                ]),

                Tab::make(trans('Images'), [
                    Images::make('Images', 'img-wedding')
                        ->conversionOnIndexView('jpg-225x225')
                        ->conversionOnDetailView('jpg-225x225')
                        ->conversionOnForm('jpg-225x225')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->setFileName(function($originalFilename, $extension, $model){
                            $info = pathinfo($originalFilename);
                            $originalFilename =  basename($originalFilename,'.'.$info['extension']);
                            return Str::slug(basename($originalFilename)) . '.' . $extension;
                        }),

                    Images::make('Images Old', 'wedding-images')
                        ->conversionOnIndexView('thumb')
                        ->conversionOnDetailView('thumb')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->help('Don\'t attache new images'),
                ]),

                Tab::make(trans('Video'), [
                    Files::make('Video', 'wedding-video'),
                    Files::make('Video 360', 'wedding-video-360'),
                ]),


                Tab::make(trans('Images 3D'), [
                    Images::make('Images 360', 'wedding-images-3d'),
                ]),

                Tab::make('Properties', array_merge([
                    Boolean::make('Active', 'is_active')->default(true),
                    Boolean::make('Has New Renders', 'has_new_renders')->default(false),
                    Select::make('Gender', 'gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                        ])
                        ->displayUsingLabels(),

                    // Gender dependencies
                    NovaDependencyContainer::make([
                        Select::make('Ring style', 'ring_style_id')
                            ->options(
                                $this->makeRingStyleSelectByGender($ring_styles, 'male')
                            )
                            ->displayUsingLabels(),
                    ])->dependsOn('gender', 'male'),

                    NovaDependencyContainer::make([
                        Select::make('Ring style', 'ring_style_id')
                            ->options(
                                $this->makeRingStyleSelectByGender($ring_styles, 'female')
                            )
                            ->displayUsingLabels(),
                    ])->dependsOn('gender', 'female'),
                    // End gender dependencies

                    BelongsTo::make('Metal', 'metal')
                        ->rules('required'),
                    Text::make('Side setting type', 'side_setting_type')
                        ->hideFromIndex(),
                    BelongsTo::make('Ring collection', 'ringCollection')
                        ->required()
                        ->hideFromIndex(),
                    Text::make('Band width', 'band_width')
                        ->nullable(),
                    Text::make('Approx Carat Weight', 'carat_weight')
                        ->hideFromIndex(),
                    BelongsTo::make('Min ring size', 'minRingSize', 'App\Nova\RingSize')
                        ->rules('required')
                        ->hideFromIndex(),
                    BelongsTo::make('Max ring size', 'maxRingSize', 'App\Nova\RingSize')
                        ->rules('required')
                        ->hideFromIndex(),
                    MorphToMany::make('Offers')
                        ->hideFromIndex(),
                    Number::make('Approx stones', 'approx_stones')
                        ->rules('max:1000'),
                    Text::make('THICKNESS', 'thickness')
                        ->rules('max:1000')->hideFromIndex(),
                    Text::make('Diamond type', 'diamond_type')
                        ->hideFromIndex(),
                ], $this->collectCustomFields(\lenal\catalog\Models\Products\Category::WEDDING)))
            ])->withToolbar()
        ];
    }

    /**
     * @param Collection $ring_styles
     * @param string $gender
     * @return array
     */
    private function makeRingStyleSelectByGender(Collection $ring_styles, string $gender): array
    {
        return $ring_styles
            ->filter(function ($ring_style) use ($gender) {
                return $ring_style instanceof WeddingRingStyle && in_array($ring_style->gender, [null, $gender]);
            })
            ->pluck('title', 'id')
            ->toArray();
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
        return [
            new WeddingMetal,
            new WeddingStyle,
            new WeddingCollection,
            new WeddingGender,
            new OffersFilter,
        ];
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
        return [
            new AttachOffer,
            new DetachOffer,
        ];
    }
}
