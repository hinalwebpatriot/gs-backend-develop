<?php

namespace lenal\sitemap\Commands;

use Illuminate\Console\Command;
use lenal\sitemap\Jobs\SitemapGenerateJob;

class SitemapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diamonds:sitemap:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

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
        SitemapGenerateJob::dispatch();
    }
}
