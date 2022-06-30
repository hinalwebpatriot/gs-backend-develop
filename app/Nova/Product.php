<?php

namespace App\Nova;

use App\Nova\Actions\AttachOffer;
use App\Nova\Actions\DetachOffer;
use App\Nova\Filters\BrandFilter;
use App\Nova\Filters\CategoryFilter;
use App\Nova\Lenses\ProductBracelet;
use App\Nova\Lenses\ProductEarring;
use App\Nova\Lenses\ProductEternity;
use App\Nova\Lenses\ProductPendant;
use App\Nova\Lenses\ProductRing;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\DeleteResourceRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use lenal\catalog\Models\Products\ProductField;
use MrMonat\Translatable\Translatable;

class Product extends Resource
{
    use CustomFieldElements;
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Products\Product';

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
        return $this->category->name;
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
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Catalog';

    public static function label()
    {
        return 'Products';
    }

    public static function usesScout()
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        /** @var ProductField[]|Collection $fields */
        $fieldGroups = ProductField::query()->get()->groupBy('category');
        $categories = \lenal\catalog\Models\Products\Category::query()
            ->get()
            ->pluck('id', 'slug')
            ->toArray();

        $customFields = [];
        $field = $request instanceof ResourceDetailRequest || $request instanceof DeleteResourceRequest ? 'category_id' : 'category';

        if ($fieldGroups->isNotEmpty()) {
            foreach ($fieldGroups as $group => $fields) {
                if (!isset($categories[$group])) {
                    continue;
                }
                $customFields[] = NovaDependencyContainer::make($this->prepareCustomFormFields($fields))
                    ->dependsOn($field, $categories[$group]);
            }
        }

        return [
            Tabs::make('Product', [
                Tab::make('General', [
                    ID::make()->sortable(),

                    Translatable::make('Item name', 'item_name')->rules('required')->hideFromIndex(),

                    Text::make('Slug', 'slug')
                        ->rules('required')
                        ->creationRules('unique:products,slug')
                        ->updateRules('unique:products,slug,{{resourceId}}')
                        ->hideFromIndex(),

                    Text::make('SKU', 'sku')
                        ->rules('required')
                        ->creationRules('unique:products,sku')
                        ->updateRules('unique:products,sku,{{resourceId}}'),

                    Text::make('Group sku', 'group_sku')->help(trans('nova.common.sku-group'))->hideFromIndex(),

                    Text::make('excl GST', 'raw_price')->rules('numeric')->sortable(),
                    Text::make('inc GSD', 'inc_price')->rules('numeric')->hideFromIndex(),
                    Number::make('Discount price', 'discount_price')->withMeta([
                        'extraAttributes' => [
                            'readonly' => true,
                        ]
                    ])
                        ->sortable()
                        ->hideWhenCreating(),


                    Translatable::make('Header', 'header')->hideFromIndex(),
                    Translatable::make('Sub header', 'sub_header')->hideFromIndex(),
                    Translatable::make('Description', 'description')->rules('max:1000')->hideFromIndex(),
                    Text::make('Delivery period (weeks)', 'delivery_period')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),
                    Text::make('Delivery period (days)', 'delivery_period_days')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),
                    Boolean::make('Is sold out', 'is_sold_out'),
                    Boolean::make('In store', 'in_store')->hideFromIndex(),
                    Boolean::make('Is top', 'is_top')->hideFromIndex(),
                    Number::make('Custom Sort Field', 'custom_sort')
                        ->rules(['required', 'integer'])
                        ->default(0)
                        ->sortable(),
                ]),

                Tab::make('Properties', array_merge([
                    Boolean::make('Active', 'is_active')->default(true),
                    Boolean::make('Has New Renders', 'has_new_renders')->default(false),
                    BelongsTo::make('Category', 'category'),

                    Select::make('Gender', 'gender')->options([
                        'neutral' => 'Neutral',
                        'female' => 'Female',
                        'male' => 'Male'
                    ])->default('female')->required(),

                    BelongsTo::make('Metal', 'metal'),

                    BelongsTo::make('Brand', 'brand')->hideFromIndex(),

                    BelongsTo::make('Style', 'style', 'App\Nova\ProductStyle')->nullable()->hideFromIndex(),

                    BelongsTo::make('Stone shape', 'stoneShape', 'App\Nova\Shape')
                        ->hideFromIndex()
                        ->nullable(),

                    Text::make('Band width', 'band_width')->rules('nullable', 'numeric', 'max:5')->hideFromIndex(),

                    BelongsTo::make('Min size', 'minSize', 'App\Nova\ProductSize')
                        ->nullable()
                        ->hideFromIndex(),

                    BelongsTo::make('Max size', 'maxSize', 'App\Nova\ProductSize')
                        ->nullable()
                        ->hideFromIndex(),

                    Text::make('Stone size', 'stone_size')
                        ->rules('nullable', 'numeric')
                        ->help('set dimension in \'ct\''),

                    Text::make('Title for center stone', 'text_for_center_stone')
                        ->rules('nullable')
                        ->hideFromIndex()
                        ->help('It shows if "Include center stone" enable'),
                    Boolean::make('Include center stone', 'is_include_center_stone')
                        ->hideFromIndex(),

                    Select::make('Setting type', 'setting_type')
                        ->options([
                            '2 Claw' => '2 Claw',
                            '3 Claw' => '3 Claw',
                            '4 Claw' => '4 Claw',
                            '5 Claw' => '5 Claw',
                            '6 Claw' => '6 Claw',
                            '8 Claw' => '8 Claw',
                            '9 Claw' => '9 Claw',
                            'Bezel set' => 'Bezel set',
                            'Half Bezel / Claw Setting' => 'Half Bezel / Claw Setting',
                        ])
                        ->hideFromIndex(),

                    Text::make('Side setting type', 'side_setting_type')->hideFromIndex(),

                    Text::make('Approx Carat Weight', 'carat_weight')->hideFromIndex(),

                    Text::make('Average Side Stone Colour', 'average_ss_colour')->hideFromIndex(),

                    Text::make('Average Side Stone Clarity', 'average_ss_clarity')->hideFromIndex(),

                    Number::make('Approx stones', 'approx_stones')->rules('max:1000'),
                ], $customFields)),

                Tab::make(trans('Images'), [
                    Images::make('Images', 'img-product')
                        ->conversionOnIndexView('jpg-225x225')
                        ->conversionOnDetailView('jpg-225x225')
                        ->conversionOnForm('jpg-225x225')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->setFileName(function($originalFilename, $extension, $model){
                            $info = pathinfo($originalFilename);
                            $originalFilename =  basename($originalFilename,'.'.$info['extension']);
                            return Str::slug(basename($originalFilename)) . '.' . $extension;
                        }),

                    Images::make('Images Old', 'product-images')
                        ->hideFromIndex()
                        ->conversionOnIndexView('thumb')
                        ->conversionOnDetailView('thumb')
                        ->singleMediaRules('dimensions:min_width=300')
                        ->help('Don\'t attache new images'),
                ]),

                Tab::make(trans('Images 3D'), [
                    Images::make('Images 360', 'product-images-3d')
                        ->hideFromIndex(),
                ]),

                Tab::make(trans('Video'), [
                    Files::make('Video', 'product-video')->hideFromIndex(),
                    Files::make('Video 360', 'product-video-360')->hideFromIndex(),
                ]),

                Tab::make(trans('Offers'), [
                    MorphToMany::make('Offers')->hideFromIndex(),
                ]),
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
        return [
            new CategoryFilter(),
            new BrandFilter(),
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
        return [
            new ProductEarring,
            new ProductPendant,
            new ProductBracelet,
            new ProductRing,
            new ProductEternity,
        ];
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
