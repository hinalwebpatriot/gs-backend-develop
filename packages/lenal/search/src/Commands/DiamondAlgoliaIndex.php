<?php

namespace lenal\search\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use lenal\blog\Models\Article;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class DiamondAlgoliaIndex extends Command
{
    private $model_dependencies = [
        'diamond'    => Diamond::class,
        'wedding'    => WeddingRing::class,
        'engagement' => EngagementRing::class,
        'blog'       => Article::class,
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diamonds:algolia:index {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add indices to algolia service.';

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
        $model_classes = is_null($this->option('model'))
            ? collect($this->model_dependencies)
            : collect($this->model_dependencies)
                ->filter(function ($model_class, $model_name) {
                    return $model_name == $this->option('model');
                });

        if ($this->confirm('This will rebuild exist indices. Do you wish to continue?')) {
            if ($model_classes->count() == 0) {
                $error_message = "Invalid model! Available models are: "
                    . implode(', ', array_keys($this->model_dependencies));
                $this->error($error_message);

                return;
            }

            $model_classes->each(function ($model_class) {
                Artisan::call('scout:flush', [
                    'model' => $model_class,
                ]);
                Artisan::call('scout:import', [
                    'model' => $model_class,
                ]);
            });
        }
    }
}
