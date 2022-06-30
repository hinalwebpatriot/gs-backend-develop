<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\offers\Models\Offer;

/**
 * Class RecalculateDiscountPricesJob
 *
 * @package lenal\catalog\Jobs
 */
class RecalculateDiscountPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $model_list;
    /**
     * @var Offer
     */
    private $model;

    /**
     * Create a new job instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->model->relationLoaded('offers')) {
            $this->model->load('offers');
        }

        $sale_offer = $this->model->offers
            ->where('enabled', 1)
            ->where('is_sale', 1)
            ->first();

        if (!is_null($this->model->discount_price) && !$sale_offer) {
            $this->model->discount_price = null;
            $this->model->save();

            return;
        }

        if ($sale_offer) {
            $raw_price = $this->model->raw_price;
            $discount = $sale_offer->discount / 100;

            $this->model->discount_price = $raw_price - ($raw_price * $discount);
            $this->model->save();
        }
    }


}
