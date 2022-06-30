<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property mixed $title
 * @property string $slug
 */
class ProductStyle extends Model implements HasMedia
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
        'title', 'slug',
    ];

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
