<?php

namespace App\Nova;

use App\Nova\Filters\DiamondCarat;
use App\Nova\Filters\DiamondClarities;
use App\Nova\Filters\DiamondColors;
use App\Nova\Filters\DiamondCuts;
use App\Nova\Filters\DiamondManufacturers;
use App\Nova\Filters\DiamondPolishes;
use App\Nova\Filters\DiamondShape;
use App\Nova\Filters\DiamondSymmetries;
use App\Nova\Filters\OfflineDiamondFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use lenal\catalog\Enums\DiamondTypeEnum;

class Diamond extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Diamonds\Diamond';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */

    public function title()
    {
        return $this->sku . ' - ' . $this->certificate_number . ' ' . $this->carat . 'ct.';
    }

    public function subtitle()
    {
        return $this->manufacturer->title;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'sku', 'certificate_number'
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Natural Diamond';
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
    public static $category = 'Diamond products';

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Diamonds', [
                Tab::make(trans('nova.diamond.tabs.general'), [
                    ID::make()->sortable(),

                    Boolean::make('Enabled'),

                    Text::make('SKU', 'stock_number')
                        ->rules('required')
                        ->creationRules('unique:diamonds,stock_number')
                        ->updateRules('unique:diamonds,stock_number,{{resourceId}}'),
                    Text::make('Certificate number', 'certificate_number')
                        ->rules('required')
                        ->creationRules('unique:diamonds,certificate_number')
                        ->updateRules('unique:diamonds,certificate_number,{{resourceId}}')
                        ->hideFromIndex(),
                    Text::make(trans('nova.diamond.raw_price'), 'raw_price')
                        ->rules('numeric')
                        ->help('Price without Tax')
                    ->sortable(),
                    Text::make(trans('nova.diamond.margin_price'), 'margin_price')
                        ->sortable()
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ]),
                    Number::make('Delivery period (weeks)', 'delivery_period')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),
                    Number::make('Delivery period (days)', 'delivery_period_days')
                        ->hideFromIndex()
                        ->rules(['nullable', 'integer'])
                        ->sortable(),
                    DateTime::make(trans('nova.diamond.created_at'), 'created_at')
                        ->hideFromIndex()
                        ->hideWhenUpdating()
                        ->hideWhenCreating(),
                    DateTime::make(trans('nova.diamond.updated_at'), 'updated_at')
                        ->hideWhenUpdating()
                        ->hideWhenCreating(),
                        Boolean::make('Is offline', 'is_offline'),
                        Boolean::make('In store', 'in_store')->hideFromIndex(),
                ]),

                Tab::make(trans('nova.diamond.tabs.images'), [
                    Images::make(trans('nova.diamond.images'), 'diamond-images')
                        ->conversionOnIndexView('thumb')
                        ->conversionOnDetailView('thumb')
                        ->singleMediaRules('dimensions:min_width=400'),
                ]),

                Tab::make(trans('nova.diamond.tabs.properties'), [
                    BelongsTo::make(trans('nova.diamond.shape'), 'shape')
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.clarity'), 'clarity')
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.color'), 'color')
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.polish'), 'polish')->hideFromIndex()
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.fluorescence'), 'fluorescence')->hideFromIndex()
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.symmetry'), 'symmetry')->hideFromIndex()
                        ->nullable(),
                    BelongsTo::make(trans('nova.diamond.culet'), 'culet')->hideFromIndex()
                        ->nullable(),
                    Text::make(trans('nova.diamond.carat'), 'carat')
                        ->rules('nullable', 'numeric')
                        ->sortable(),
                    BelongsTo::make(trans('nova.diamond.cut'), 'cut')
                        ->nullable(),
                    Text::make(trans('nova.diamond.length'), 'length')->hideFromIndex(),
                    Text::make(trans('nova.diamond.width'), 'width')->hideFromIndex(),
                    Text::make(trans('nova.diamond.height'), 'height')->hideFromIndex(),
                    Text::make(trans('nova.diamond.size_ratio'), 'size_ratio')->hideFromIndex(),
                    Text::make(trans('nova.diamond.girdle'), 'girdle')->hideFromIndex(),
                    Text::make(trans('nova.diamond.video'), 'video')->hideFromIndex(),
                    Text::make(trans('nova.diamond.certificate'), 'certificate')->hideFromIndex(),
                    Text::make(trans('nova.diamond.depth'), 'depth')->hideFromIndex()
                        ->rules('nullable', 'numeric')
                        ->sortable(),
                    Text::make(trans('nova.diamond.table'), 'table')->hideFromIndex()
                        ->rules('nullable', 'numeric')
                        ->sortable(),
                    BelongsTo::make(trans('nova.diamond.manufacturer'), 'manufacturer')
                        ->nullable(),
                ]),

                Tab::make(trans('Video'), [
                    File::make('Alternative Video', 'video')
                        ->disk(config('filesystems.cloud'))
                        ->path('diamond-videos')
                ]),
            ])->withToolbar()
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', DiamondTypeEnum::NATURAL()->getValue());
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
            new OfflineDiamondFilter,
            new DiamondManufacturers,
            new DiamondCuts,
            new DiamondColors,
            new DiamondSymmetries,
            new DiamondClarities,
            new DiamondPolishes,
            new DiamondShape,
            new DiamondCarat,
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
        return [];
    }
}
