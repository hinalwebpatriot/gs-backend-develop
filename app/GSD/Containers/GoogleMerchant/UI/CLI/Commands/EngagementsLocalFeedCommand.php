<?php


namespace GSD\Containers\GoogleMerchant\UI\CLI\Commands;


use GSD\Containers\GoogleMerchant\Components\Feed;
use GSD\Containers\GoogleMerchant\Data\Repositories\EngagementsRepository;
use GSD\Ship\Parents\Commands\Command;
use lenal\catalog\Facades\CommonHelper;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EngagementsLocalFeedCommand extends Command
{
    protected $signature   = 'google-merchant:generate:engagements-local-feed';
    protected $description = 'Generate engagements feed for google merchant';

    private EngagementsRepository $repository;

    private string $descriptionProduct = 'Buy %s engagement ring from Australian designers with top quality guarantee on the official shop.';

    public function __construct(EngagementsRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function handle()
    {
        $products = $this->repository->getOldRenderList();
        $feed = new Feed('gm-engagements-local', 'engagements');
        foreach ($products as $product) {
            /** @var Media|null $image */
            $image = $product->getFirstMedia('img-engagement');
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
        $feed->render('feedLocal');
    }
}
