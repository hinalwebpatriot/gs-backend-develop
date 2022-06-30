<?php

namespace lenal\reviews\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property WeddingRing|EngagementRing|Product $product
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Review extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'author_name',
        'author_email',
        'text',
        'rate',
        'product_id',
        'product_type',
        'is_active'
    ];

    public function product()
    {
        return $this->morphTo();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('photos')
            ->useDisk(config('filesystems.cloud'));
    }

}
