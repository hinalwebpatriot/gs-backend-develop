<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Products\Product;

class ProductVideoFromPath implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productId;
    private $videos = [];

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param array images
     */
    public function __construct($productId, $videos = [])
    {
        $this->productId = $productId;
        $this->videos = $videos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var Product $product */
            $product = Product::query()->find($this->productId);

            if (!$product) {
                return ;
            }

            foreach ($this->videos as $videoPath) {
                if ($videoPath && is_file($videoPath)) {
                    $product->addMedia($videoPath)->toMediaCollection('product-video');
                }

            }
        } catch (\Exception $e) {
            logger()->channel('import-prod')->debug($e->getMessage());
        }
    }
}
