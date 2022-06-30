<?php

namespace lenal\catalog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use lenal\catalog\Jobs\ResizeCommonImageJob;
use lenal\catalog\Jobs\ResizeImagesJob;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Shape;

class ResizeDiamondImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resize-diamond-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize diamond image';

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
        $colors = Color::query()->select('slug')->get()->toArray();
        $shapes = Shape::query()->select('slug')->get()->toArray();

        foreach ($shapes as $shape) {
            foreach ($colors as $color) {
                $imagePath = 'shapes' . '/' . Str::title($shape['slug']) . '/' . Str::title($color['slug']) . '.png';

                ResizeCommonImageJob::dispatch($imagePath, 115, 115, 'feed_');
            }
        }
    }
}
