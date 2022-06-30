<?php

namespace lenal\offers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use lenal\offers\Observers\OfferCollectionsObserver;

/**
 * @property integer $id
 * @property integer $collection_id
 * @property integer $offer_id
 */
class OfferCollection extends Model
{
    use AsPivot;

    protected $table = 'offer_ring_collection';
    public $timestamps = false;

    protected $fillable = [
        'collection_id',
        'offer_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(OfferCollectionsObserver::class);
    }
}
