<?php

namespace lenal\catalog\Models\Rings;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 */
class WeddingRingStyle extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $translatable = ['title'];

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'gender',
    ];

    public function rings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\WeddingRing', 'ring_style_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk(config('filesystems.cloud'))
            ->singleFile();

        $this->addMediaCollection('image-hover')
            ->useDisk(config('filesystems.cloud'))
            ->singleFile();
    }
}

