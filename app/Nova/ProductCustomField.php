<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use lenal\catalog\Models\Products\Category as CategoryEntity;
use MrMonat\Translatable\Translatable;

class ProductCustomField extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Products\ProductField';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $category = 'Catalog';

    public static function label()
    {
        return 'Custom fields';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'category',
        'label',
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
            Select::make('Category', 'category')
                ->options(Arr::except(CategoryEntity::availableCategories(), CategoryEntity::DIAMONDS))
                ->rules('required'),
            Translatable::make('Label', 'label'),
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
