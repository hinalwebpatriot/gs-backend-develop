<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var WeddingRing|EngagementRing|Diamond|Product */
    private $model;
    private $media;

    /**
     * Create a new job instance.
     *
     * @param $model
     * @param $media
     */
    public function __construct($model, $media)
    {
        $this->model = $model;
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $medias = $this->model->getMedia($this->media);

        $medias->each(function ($media) {
            $conversion = (new ConversionCollection())->createForMedia($media);
            dispatch(new PerformConversionsJob($conversion, $media));
        });

        if ($this->media != 'diamond-images') {
            $this->model->flushImageCache();
        }
    }
}
