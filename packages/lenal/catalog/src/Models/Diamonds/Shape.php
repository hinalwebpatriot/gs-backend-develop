<?php

namespace lenal\catalog\Models\Diamonds;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $slug
 * @property string $title
 */
class Shape extends Model  implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public $timestamps = false;

    public $translatable = ['title', 'alt'];

    protected $fillable = [
        'title', 'preview_image', 'slug', 'alt'
    ];

    public function diamonds()
    {
        return $this->hasMany('lenal\catalog\Models\Diamonds\Diamond');
    }

    public function engagementRings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\EngagementRing', 'stone_shape_id');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('diamond-images')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('banner')
            ->useDisk(config('filesystems.cloud'));
    }
}

