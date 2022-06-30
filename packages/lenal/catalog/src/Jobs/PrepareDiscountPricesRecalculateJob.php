<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\offers\Models\Offer;

/**
 * Class PrepareDiscountPricesRecalculateJob
 *
 * @package lenal\catalog\Jobs
 */
class PrepareDiscountPricesRecalculateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $model_list;
    /**
     * @var Offer|null
     */
    private $offer;

    /**
     * Create a new job instance.
     *
     * @param array $model_list
     * @param Offer|null $offer
     */
    public function __construct(array $model_list, ?Offer $offer)
    {
        $this->model_list = collect($model_list);
        $this->offer = $offer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model_list->each(function (string $model) {
            if (!class_exists($model)) {
                return;
            }

            $model_builder = $model::with('offers');

            if (!is_null($this->offer)) {
                $model_builder->whereHas('offers', function ($query) {
                    $query->where('offers.id', $this->offer->id);
                });
            }

            $model_builder
                ->get(['id', 'raw_price', 'discount_price'])
                ->each(function ($model_item) {
                    RecalculateDiscountPricesJob::dispatch($model_item);
                });
        });
    }


}
