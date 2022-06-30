<?php


namespace lenal\catalog\DTO;


use lenal\catalog\Models\CartItem;

class OrderDetailsItemDTO
{
    public string $name;
    public string $url;
    public ?string $previewImage;
    public string $sku;
    public float $cost;
    public string $costCurrency;
    public ?string $productMetal;
    public ?string $productBandWidth;
    public ?string $productCarat;
    public ?string $productColor;
    public ?string $productShape;
    public ?string $ringSize;
    public ?string $ringEngraving;
    public ?string $ringEngravingFont;

    public static function make(CartItem $item, $currency)
    {
        if (!$item->product) {
            return null;
        }
        $dto = new static();
        $dto->name = $item->product->title;
        $dto->sku = $item->product->sku;
        $dto->cost = $item->price;
        $dto->costCurrency = $currency;
        $dto->url = config('app.frontend_url').'/'.$item->product->getUri();
        $dto->previewImage = $item->product->previewImageUrl();
        if ($item->product->metal) {
            $dto->productMetal = $item->product->metal->title;
        }
        if ($item->product->band_width) {
            $dto->productBandWidth = $item->product->band_width;
        }
        if ($item->product->carat) {
            $dto->productCarat = $item->product->carat;
        }
        if ($item->product->color) {
            $dto->productColor = $item->product->color->title;
        }
        if ($item->product->shape) {
            $dto->productShape = $item->product->shape->title;
        }

        if ($item->size_slug) {
            $dto->ringSize = $item->size_slug;
            if ($item->size) {
                $dto->ringSize = $item->size->getTitle();
            }
        }

        if ($item->engraving) {
            $dto->ringEngraving = $item->engraving;
            $dto->ringEngravingFont = $item->engraving_font;
        }

        return $dto;
    }
}
