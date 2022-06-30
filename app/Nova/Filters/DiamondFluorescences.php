<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use lenal\catalog\Models\Diamonds\Fluorescence;

class DiamondFluorescences extends Filter
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
        return $query->where('fluorescence_id', $value);
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
        $fluorescences = Fluorescence::all();

        $fluorescences_options = [];

        foreach ($fluorescences as $fluorescence) {
            $fluorescences_options[$fluorescence->getTranslation('title', 'en')] = $fluorescence->id;
        }

        return $fluorescences_options;
    }
}
