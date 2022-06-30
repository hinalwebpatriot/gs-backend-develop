<?php


namespace GSD\Containers\GoogleMerchant\Components;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Feed
{
    private array  $products = [];
    private string $nameFeed;
    private string $feedType;
    private string $disk     = 'google-shopping';

    public function __construct(string $nameFeed, string $feedType)
    {
        $this->nameFeed = $nameFeed;
        $this->feedType = $feedType;
    }

    public function addProduct(
        string $sku,
        string $title,
        string $description,
        string $linkProduct,
        string $linkImage,
        float $price,
        ?float $sale_price,
        int $category,
        bool $hasNewRenders,
        bool $isBest
    ) {
        $this->products[] = [
            'id'              => $sku,
            'title'           => $title,
            'description'     => strip_tags($description),
            'linkProduct'     => $linkProduct,
            'linkImage'       => $linkImage,
            'price'           => $price,
            'sale_price'      => $sale_price,
            'category'        => $category,
            'has_new_renders' => $hasNewRenders,
            'is_best_for_merchant' => $isBest
        ];

        return $this;
    }

    public function render(string $view = 'feed')
    {
        Storage::disk($this->disk)->put(
            sprintf('gshopping/%s.xml', $this->nameFeed),
            view(sprintf('GoogleMerchant::%s', $view), ['products' => $this->products, 'feedType' => $this->feedType])->render()
        );
    }

    public function renderCsv()
    {
        $f = fopen('php://memory', 'r+');
        if (fputcsv($f, ['store_code', 'itemid', 'price', 'quantity', 'availability']) === false) {
            throw new \Exception('Error make CSV');
        }
        foreach ($this->products as $product) {
            $fields = [
                101, $product['id'], ($product['sale_price'] ?: $product['price']), 3, 'in_stock'
            ];
            if (fputcsv($f, $fields) === false) {
                throw new \Exception('Error make CSV');
            }
        }
        rewind($f);
        $csv_line = stream_get_contents($f);
        Storage::disk($this->disk)->put(
            sprintf('gshopping/%s.csv', $this->nameFeed),
            rtrim($csv_line)
        );
        fclose($f);
    }
}
