<?php

namespace lenal\catalog\Commands;

use Illuminate\Console\Command;
use lenal\catalog\Jobs\ResizeImageJob;
use lenal\catalog\Models\Diamonds\Diamond;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ConfigImageResize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config-image-resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Config resize image';

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
        Diamond::query()
            ->whereRaw("created_at > '2019-10-23 13:42:09' AND created_at < '2019-10-27 13:42:09'")
            //->whereRaw('`created_at` < (NOW() - INTERVAL 2 DAY)')
            ->chunk(1000, function($models) use (&$total) {
                foreach ($models as $model) {
                    $count = Media::query()
                        ->where('model_id', $model->id)
                        ->where('model_type', 'lenal\catalog\Models\Diamonds\Diamond')
                        ->count();

                    if ($count == 1) {
                        ResizeImageJob::dispatch($model, 'diamond-images');
                    }
                }
            });
    }
}
