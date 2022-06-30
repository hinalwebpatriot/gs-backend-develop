<?php


namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Compare;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\offers\Models\Offer;
use lenal\reviews\Models\Review;
use lenal\seo\Models\Meta;

/**
 * @property Meta $metal
 * @property Category $category
 * @property Brand $brand
 * @property ProductStyle $style
 * @property Offer[]|Collection $offers
 * @property Shape $stoneShape
 * @property ProductSize $minSize
 * @property ProductSize $maxSize
 * @property Compare[] $compares
 * @property CartItem[] $carts
 * @property Review[] $reviews
 * @property StaticBlock[] $staticBlocks
 * @property ProductFieldAssign[]|Collection|MorphMany $customFields
 * @mixin Product
 */
trait ProductCompositions
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metal()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\Metal');
    }

    public function brand()
    {
        return $this->belongsTo('lenal\catalog\Models\Products\Brand');
    }

    public function category()
    {
        return $this->belongsTo('lenal\catalog\Models\Products\Category');
    }

    public function style()
    {
        return $this->belongsTo('lenal\catalog\Models\Products\ProductStyle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maxSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'model', 'offer_relations');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stoneShape()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Shape', 'shape_id');
    }

    public function compares()
    {
        return $this->morphMany(Compare::class, 'product');
    }

    public function carts()
    {
        return $this->morphMany(CartItem::class, 'product');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'product');
    }

    public function customFields()
    {
        return $this->morphMany(ProductFieldAssign::class, 'product_field', 'product_type', 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staticBlocks()
    {
        return $this->belongsToMany(StaticBlock::class, 'static_blocks_products');
    }

    /**
     * @param Builder $query
     */
    public function scopeWithResourceRelation(Builder $query)
    {
        $query->with([
            'brand',
            'category',
            'style',
            'metal',
        ]);
    }
}
