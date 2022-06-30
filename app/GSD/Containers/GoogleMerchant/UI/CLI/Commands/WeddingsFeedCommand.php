<?php


namespace GSD\Containers\GoogleMerchant\UI\CLI\Commands;


use GSD\Containers\GoogleMerchant\Components\Feed;
use GSD\Containers\GoogleMerchant\Data\Repositories\WeddingsRepository;
use GSD\Ship\Parents\Commands\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WeddingsFeedCommand extends Command
{
    protected $signature   = 'google-merchant:generate:weddings-feed';
    protected $description = 'Generate weddings feed for google merchant';

    private WeddingsRepository $repository;

    private string $descriptionProduct = 'Buy %s wedding ring from Australian designers with top quality guarantee on the official website.';

    public function __construct(WeddingsRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function handle()
    {
        $products = $this->repository->getList();
        $feed = new Feed('gm-weddings', 'weddings');
        foreach ($products as $product) {
            /** @var Media|null $image */
            $image = $product->getFirstMedia('img-wedding');
            $feed->addProduct(
                $product->sku,
                $product->title,
                sprintf($this->descriptionProduct, $product->title),
                config('app.frontend_url').'/'.$product->getUri(),
                $image ? $image->getFullUrl('webp-900x900') : '',
                $product->old_calculated_price ?? $product->calculated_price,
                $product->old_calculated_price ? $product->calculated_price : null,
                200,
                $product->has_new_renders,
                false
            );
        }
        $feed->render();
    }
}
