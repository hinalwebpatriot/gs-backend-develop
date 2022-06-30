<?php

namespace lenal\offers\Models;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Products\Category;
use lenal\offers\Observers\OfferBrandsObserver;

/**
 * @property integer $id
 * @property integer $brand_id
 * @property integer $category_id
 * @property integer $offer_id
 */
class OfferBrand extends Model
{
    protected $table = 'offer_brands';
    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'category_id',
        'offer_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(OfferBrandsObserver::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
