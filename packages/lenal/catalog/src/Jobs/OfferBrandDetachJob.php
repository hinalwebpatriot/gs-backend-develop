<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Products\Product;

class OfferBrandDetachJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $brandId;
    private $categoryId;
    private $offerId;

    /**
     * Create a new job instance.
     *
     * @param int $brandId
     * @param int $categoryId
     * @param int $offerId
     */
    public function __construct($brandId, $categoryId, $offerId)
    {
        $this->brandId = $brandId;
        $this->categoryId = $categoryId;
        $this->offerId = $offerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Product::query()
            ->scopes(['brand' => $this->brandId, 'category' => $this->categoryId])
            ->chunk(500, function(Collection $models) {
                $models->each(function($model) {
                    /** @var Product $model */
                    $model->offers()->detach($this->offerId);
                });
            });
    }
}
