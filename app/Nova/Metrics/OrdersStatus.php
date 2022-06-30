<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use lenal\catalog\Models\Order;
use lenal\catalog\Models\Status;

class OrdersStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $statuses = Status::query()->select(['id', 'title'])->get()->keyBy('id')
            ->map(function ($value) {
                return $value['title'];
            })->toArray();
        return $this->count($request, Order::class, 'status')
            ->label(function ($value) use ($statuses) {
                return isset($statuses[$value]) ? $statuses[$value] : 'Unknown';
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
        return 'orders-status';
    }
}
