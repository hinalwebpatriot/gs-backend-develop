<?php

namespace lenal\catalog\Jobs;

use App\Helpers\ImageHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ResizeCommonImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;
    private $prefix;
    private $width;
    private $height;

    /**
     * Create a new job instance.
     *
     * @param string $path
     * @param string $width
     * @param string $height
     * @param string $prefix
     */
    public function __construct($path, $width, $height, $prefix)
    {
        $this->path = $path;
        $this->prefix = $prefix;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ImageHelper::cropCloudImage($this->path, $this->width, $this->height, $this->prefix);
    }
}
