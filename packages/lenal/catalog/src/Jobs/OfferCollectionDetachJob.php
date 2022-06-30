<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Rings\EngagementRing;

class OfferCollectionDetachJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $collectionId;
    private $offerId;

    /**
     * Create a new job instance.
     *
     * @param int $collectionId
     * @param int $offerId
     */
    public function __construct($collectionId, $offerId)
    {
        $this->collectionId = $collectionId;
        $this->offerId = $offerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EngagementRing::query()->where('ring_collection_id', $this->collectionId)->chunk(1000, function(Collection $models) {
            $models->each(function($model) {
                /** @var EngagementRing $model */
                $model->offers()->detach($this->offerId);
            });
        });
    }
}
