<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DiamondCarat extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    protected $carat = [
        0=> ['min' => 0.0, 'max' => 0.29],
        1=> ['min' => 0.3, 'max' => 0.39],
        2=> ['min' => 0.4, 'max' => 0.49],
        3=> ['min' => 0.5, 'max' => 0.69],
        4=> ['min' => 0.7, 'max' => 0.89],
        5=> ['min' => 0.9, 'max' => 0.99],
        6=> ['min' => 1.00, 'max' => 1.49],
        7=> ['min' => 1.5, 'max' => 1.99],
        8=> ['min' => 2.0, 'max' => 2.99],
        9=> ['min' => 3.0, 'max' => 3.99],
        10=> ['min' => 4.0, 'max' => 4.99],
        11=> ['min' => 5.0, 'max' => 5.99],
        12=> ['min' => 6.0, 'max' => 6.99],
        13=> ['min' => 7.0, 'max' => 9.99],
        14=> ['min' => 10.0, 'max' => 10.99],
        15=> ['min' => 11.0, 'max' => 20.99],
        16=> ['min' => 21.0, 'max' => 30.0],
    ];

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
        return $query->where('carat', ">=", $this->carat[$value]["min"])
            ->where('carat', "<=", $this->carat[$value]["max"]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $color_options = [];
        foreach ($this->carat as $k=>$carat) {
            $color_options[$carat["min"]."-".$carat["max"]] = $k;
        }

        return $color_options;
    }
}
