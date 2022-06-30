<?php

namespace lenal\catalog\Models\Rings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Class Metal
 *
 * @property int    $id
 * @property string $title
 * @property string $slug
 */
class Metal extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    const CACHE_KEY = 'cache_metal';

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
        'title', 'slug', 'engagement_off',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function (self $model) {
            Cache::forget(static::CACHE_KEY);
            static::getList();
        });
    }

    public static function getList($ids = [], $withImage = true)
    {
        if (!Cache::has(self::CACHE_KEY)) {
            $metals = self::query()
                ->get()
                ->map(function (self $item) {
                    return [
                        'id'    => $item->id,
                        'title' => $item->title,
                        'slug'  => $item->slug,
                        'image' => $item->getFirstMedia('image')->getFullUrl()
                    ];
                })
                ->values()
                ->toArray();
            Cache::forever(self::CACHE_KEY, $metals);
        }

        $metals = collect(Cache::get(self::CACHE_KEY))->keyBy('id');

        if (!$withImage) {
            $metals = $metals->map(function ($item) {
                unset($item['image']);
                return $item;
            });
        }

        if ($ids) {
            $sortIds = array_flip($ids);
            $metals = $metals->whereIn('id', $ids)->sortBy(function ($item) use ($sortIds) {
                return $sortIds[$item['id']];
            });
        }

        return $metals->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function engagement_rings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\EngagementRing');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wedding_rings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\WeddingRing');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk(config('filesystems.cloud'))
            ->singleFile();
    }
}

