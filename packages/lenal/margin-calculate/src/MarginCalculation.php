<?php

namespace lenal\MarginCalculate;

use Illuminate\Database\Eloquent\Builder;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\MarginCalculate\Models\MarginCalculate;

/**
 * Trait MarginCalculation
 *
 * @package lenal\MarginCalculate
 */
trait MarginCalculation
{
    /**
     * @param Builder         $query
     * @param MarginCalculate $margin_calculate
     */
    public function scopeSearchByMargin(Builder $query, MarginCalculate $margin_calculate)
    {
        $round_shape_id = Shape::where('slug', 'round')->first()->id;
        $manufacturer_id = $margin_calculate->manufacturer_id;

        if (!is_null($manufacturer_id)) {
            $query->where('manufacturer_id', $manufacturer_id);
        }

        $query
            ->whereBetween('carat', [
                $margin_calculate->carat_min,
                $margin_calculate->carat_max,
            ])
            ->where('clarity_id', $margin_calculate->clarity_id)
            ->where('color_id', $margin_calculate->color_id);

        $margin_calculate->is_round
            ? $query->where('shape_id', $round_shape_id)
            : $query->where('shape_id', '!=', $round_shape_id);
    }
}