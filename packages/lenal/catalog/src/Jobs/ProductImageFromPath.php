<?php

namespace lenal\catalog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use lenal\catalog\Models\Products\Product;

class ProductImageFromPath implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productId;
    private $images = [];

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param array images
     */
    public function __construct($productId, $images = [])
    {
        $this->productId = $productId;
        $this->images = $images;
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

            foreach ($this->images as $imagePath) {
                if ($imagePath && is_file($imagePath)) {
                    $product->addMedia($imagePath)
                        ->withResponsiveImages()
                        ->toMediaCollection('product-images');
                }

            }
        } catch (\Exception $e) {
            logger()->channel('import-prod')->debug($e->getMessage());
        }
    }
}
