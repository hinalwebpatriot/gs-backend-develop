<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use lenal\catalog\Models\Diamonds\Manufacturer;

class DiamondManufacturers extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('manufacturer_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        // TODO change this to helper method
        $manufacturers = Manufacturer::all();

        $manufacturer_options = [];

        foreach ($manufacturers as $manufacturer) {
            $manufacturer_options[$manufacturer->title] = $manufacturer->id;
        }

        return $manufacturer_options;
    }
}
