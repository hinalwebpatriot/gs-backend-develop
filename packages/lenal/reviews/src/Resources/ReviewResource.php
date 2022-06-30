<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 2/13/19
 * Time: 2:53 PM
 */

namespace lenal\reviews\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_created' => $this->created_at->timestamp,
            'title' => $this->title,
            'author_name' => $this->author_name,
            'text' => $this->text,
            'rate' => $this->rate,
            'photos' => $this->media->map(function ($media) { return $media->getFullUrl(); }),
            'product' => [
                'id' => $this->product_id,
                'h1' => $this->resource->product->h1 ?? null,
                'h2' => $this->resource->product->h2 ?? null,
                'slug' => $this->resource->product ?? null,
                'type' => $this->getProductType($this->product_type)
            ]
        ];
    }

    private function getProductType($productClass)
    {
        $types = [
            Diamond::class => 'diamonds',
            EngagementRing::class => 'engagement-rings',
            WeddingRing::class => 'wedding-rings',
            Product::class => 'products',
        ];
        return $types[$productClass];
    }
}