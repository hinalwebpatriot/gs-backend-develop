<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Products\Product;

class ProductImageFromUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productId;
    private $imageUrls = [];

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param array $imageUrls
     */
    public function __construct($productId, $imageUrls)
    {
        $this->productId = $productId;
        $this->imageUrls = $imageUrls;
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

            foreach ($this->imageUrls as $imageUrl) {
                if ($imageUrl) {
                    $product->addMediaFromUrl($imageUrl)
                        ->withResponsiveImages()
                        ->toMediaCollection('product-images');
                }

            }
        } catch (\Exception $e) {
            logger()->channel('import-prod')->debug($e->getMessage());
        }
    }
}
