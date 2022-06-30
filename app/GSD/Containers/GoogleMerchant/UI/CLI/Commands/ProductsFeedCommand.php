<?php


namespace GSD\Containers\GoogleMerchant\UI\CLI\Commands;


use GSD\Containers\GoogleMerchant\Components\Feed;
use GSD\Containers\GoogleMerchant\Data\Repositories\ProductsRepository;
use GSD\Ship\Parents\Commands\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductsFeedCommand extends Command
{
    protected $signature   = 'google-merchant:generate:products-feed';
    protected $description = 'Generate products feed for google merchant';

    private ProductsRepository $repository;

    private string $descriptionProduct = 'Buy %s fashion diamond jewellery from Australian designers with top quality guarantee on the official website.';

    private array $categories = [
        1 => 194,
        2 => 192,
        3 => 191,
        4 => 200
    ];

    public function __construct(ProductsRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function handle()
    {
        $products = $this->repository->getList();
        $feed = new Feed('gm-products', 'jewelery');
        foreach ($products as $product) {
            /** @var Media|null $image */
            $image = $product->getFirstMedia('img-product');
            $feed->addProduct(
                $product->sku,
                $product->title,
                sprintf($this->descriptionProduct, $product->title),
                config('app.frontend_url').'/'.$product->getUri(),
                $image ? $image->getFullUrl('webp-900x900') : '',
                $product->old_calculated_price ?? $product->calculated_price,
                $product->old_calculated_price ? $product->calculated_price : null,
                $this->getCategory($product->category_id),
                $product->has_new_renders,
                false
            );
        }
        $feed->render();
    }

    private function getCategory($categoryId)
    {
        return $this->categories[$categoryId] ?? 188;
    }
}
