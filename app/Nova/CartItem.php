<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $product_type
 * @property integer $product_id
 * @property integer $quantity
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $size_slug
 * @property integer $order_id
 * @property number $price
 * @property string $engraving
 * @property string $engraving_font
 */
class CartItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\CartItem';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public function title()
    {
        return $this->id . ' '
            . $this->product_id;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $globallySearchable = false;

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
            BelongsTo::make('Order', 'order')->searchable(),
            Text::make('Product Id', 'product_id')->rules('required'),
            Text::make('Order id', 'order_id')->rules('required'),
            Text::make('Size slug', 'size_slug')->rules('required'),
            Text::make('Price', 'price')->rules('required'),
            Text::make('Engraving', 'engraving')
                ->rules('nullable')
                ->hideFromIndex(),
            Text::make('Engraving Font', 'engraving_font')
                ->rules('nullable')
                ->hideFromIndex(),
            Text::make('Engraving', function() {
                    if ($this->engraving) {
                        return 'Text: ' . $this->engraving . '<br> Font: ' . $this->engraving_font;
                    }
                    return '—';
                })
                ->onlyOnIndex()
                ->asHtml(),
            Text::make('User', 'user_id')->rules('required'),
            MorphTo::make('Product')->display("slug"),
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
