<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Jobs\ResizeImageJob;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class ResizeImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Model
     */
    private $model;
    private $media;

    protected static $class = [
        'engagement-images'=> EngagementRing::class,
        'wedding-images'=> WeddingRing::class,
        'diamond-images'=> Diamond::class,
        'product-images'=> Product::class,
    ];

    /**
     * Create a new job instance.
     *
     * @param $media
     */
    public function __construct($media)
    {
        $this->model = self::$class[$media];
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model::query()->chunk(200, function(Collection $models) {
            $models->each(function($model) {
                ResizeImageJob::dispatch($model, $this->media);
            });
        });
    }
}
