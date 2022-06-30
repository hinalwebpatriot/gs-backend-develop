<?php

namespace lenal\landings\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $slug
 * @property mixed $header
 * @property mixed $meta_title
 * @property mixed $meta_keywords
 * @property mixed $meta_description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property LandingRing[]|Collection $ringAssign
 * @property LandingDiamond[]|Collection $diamondAssign
 * @property EngagementRing[]|Collection $rings
 * @property Diamond[]|Collection $diamonds
 */
class Landing extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    const MEDIA_COLLECTION = 'landing-images';

    public $translatable = [
        'header',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'header' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
    ];

    protected $fillable = [
        'header',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function rings()
    {
        return $this->belongsToMany(EngagementRing::class, 'landing_rings', 'landing_id', 'ring_id');
    }

    public function diamonds()
    {
        return $this->belongsToMany(Diamond::class, 'landing_diamonds', 'landing_id', 'diamond_id');
    }

    public function ringAssign()
    {
        return $this->hasMany(LandingRing::class);
    }

    public function diamondAssign()
    {
        return $this->hasMany(LandingDiamond::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)
            ->useDisk(config('filesystems.cloud'))
            ->singleFile();
    }

    public function image()
    {
        $image = $this->getMedia(self::MEDIA_COLLECTION)->first();

        return $image ? $image->getFullUrl() : asset('files/no_image_placeholder.png');
    }
}
