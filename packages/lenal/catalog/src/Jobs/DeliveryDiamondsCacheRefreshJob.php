<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use lenal\catalog\Jobs\ResizeImageJob;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class DeliveryDiamondsCacheRefreshJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Diamond::query()->chunk(200, function (Collection $models) {
            $models->each(function (Diamond $model) {
                Cache::forget($model->getDeliveryKeyCache());
                $model->getEstimateDeliveryTime();
            });
        });
    }
}
