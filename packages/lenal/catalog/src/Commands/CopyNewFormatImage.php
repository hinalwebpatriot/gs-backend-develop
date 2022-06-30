<?php

namespace lenal\catalog\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use lenal\catalog\Jobs\ResizeImageJob;
use lenal\catalog\Jobs\ResizeImagesJob;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class CopyNewFormatImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:copy-new-format';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'copy images to new format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->writeln('Engagements');
        EngagementRing::query()->chunk(200, function(Collection $models) {
            $progressBar = $this->output->createProgressBar($models->count());
            $models->each(function(EngagementRing $model) use ($progressBar) {
                if (!$model->hasMedia('img-engagement')) {
                    $old = $model->getMedia('engagement-images');
                    foreach ($old as $item) {
                        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $item */
                        $item->copy($model, 'img-engagement');
                    }
                }
                $progressBar->advance();
            });
            $progressBar->finish();
        });
        $this->output->writeln('Weddings');
        WeddingRing::query()->chunk(200, function(Collection $models) {
            $progressBar = $this->output->createProgressBar($models->count());
            $models->each(function(WeddingRing $model) use ($progressBar) {
                if (!$model->hasMedia('img-wedding')) {
                    $old = $model->getMedia('wedding-images');
                    foreach ($old as $item) {
                        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $item */
                        $item->copy($model, 'img-wedding');
                    }
                }
                $progressBar->advance();
            });
            $progressBar->finish();
        });
        $this->output->writeln('Products');
        Product::query()->chunk(200, function(Collection $models) {
            $progressBar = $this->output->createProgressBar($models->count());
            $models->each(function(Product $model) use ($progressBar) {
                if (!$model->hasMedia('img-product')) {
                    $old = $model->getMedia('product-images');
                    foreach ($old as $item) {
                        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $item */
                        $item->copy($model, 'img-product');
                    }
                }
                $progressBar->advance();
            });
            $progressBar->finish();
            $this->output->writeln('');
        });
        return Command::SUCCESS;
    }
}
