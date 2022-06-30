<?php

namespace lenal\blocks\Models;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class StaticBlock extends Model  implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public $timestamps = false;

    public $translatable = ['title', 'text', 'video_link','link', 'link_text', 'subtitle'];

    protected $casts = ['text' => 'array'];

    public function dynamic_page_block() {
        return $this->belongsTo(DynamicPage::class, 'dynamic_page_id');
    }

    public function blockDiamonds()
    {
        return $this->belongsToMany(Diamond::class, 'static_blocks_diamonds');
    }

    public function blockEngagementRings()
    {
        return $this->belongsToMany(EngagementRing::class, 'static_blocks_engagement_rings');
    }

    public function blockWeddingRings()
    {
        return $this->belongsToMany(WeddingRing::class, 'static_blocks_wedding_rings');
    }

    public function blockProducts()
    {
        return $this->belongsToMany(Product::class, 'static_blocks_products');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('static-images-3d')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('certificate_file')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('image')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('complete_look')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('promo')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('slider-feed')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('guide-pdf')
            ->useDisk(config('filesystems.cloud'));
    }

}
