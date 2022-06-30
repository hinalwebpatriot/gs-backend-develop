<?php

namespace lenal\catalog\Commands;

use Illuminate\Console\Command;
use lenal\catalog\Jobs\ResizeImagesJob;

class ResizeImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rings:resize:image {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize image rings';

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
        $model = $this->argument('model');
        ResizeImagesJob::dispatch($model);
    }
}
