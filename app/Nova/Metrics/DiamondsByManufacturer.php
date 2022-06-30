<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Manufacturer;

class DiamondsByManufacturer extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $manufacturers = Manufacturer::query()->select(['id', 'title'])->get()->keyBy('id')
            ->map(function ($value) {
                return $value['title'];
            })->toArray();
        return $this->count($request, Diamond::class, 'manufacturer_id')
            ->label(function ($value) use ($manufacturers) {
                return isset($manufacturers[$value]) ? $manufacturers[$value] : 'Unknown';
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'diamonds-by-manufacturer';
    }
}
