<?php

namespace lenal\offers\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Rings\RingCollection;
use Spatie\Translatable\HasTranslations;

/**
 * Class Offer
 *
 * @property int    $id
 * @property string $title
 * @property string $description
 * @property string $slug
 * @property int    $is_sale
 * @property int    $discount
 * @package lenal\offers\Models
 */
class Offer extends Model
{
    use HasTranslations;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'slug',
        'is_sale',
        'discount',
    ];

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'description',
    ];

    public function scopeWithActiveOrder(Builder $builder)
    {
        $builder
            ->where('enabled', 1)
            ->orderBy('sort');
    }

    public function collections()
    {
        return $this->belongsToMany(RingCollection::class, null, 'offer_id', 'collection_id')
            ->using(OfferCollection::class)
            ->withPivot('id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'offer_brands', 'offer_id', 'brand_id')
            ->using(OfferBrand::class)
            ->withPivot('id');
    }

    public function amountWithDiscount($amount)
    {
        return $amount - ($amount * ($this->discount / 100));
    }
}