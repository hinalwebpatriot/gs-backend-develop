<?php

namespace lenal\catalog\Models\Rings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 * @property string $slug
 * @package lenal\catalog\Models\Rings
 */
class RingCollection extends Model
{
    use HasTranslations;

    public $timestamps = false;

    public $translatable = ['title', 'description', 'story_title', 'story_video', 'story_text'];

    protected $fillable = [
        'title', 'description', 'slug'
    ];

    public function engagementRings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\EngagementRing');
    }

    public function weddingRings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\WeddingRing', 'ring_collection_id');
    }

    public static function shortStructure()
    {
        return static::query()->select('id', 'title', 'slug')->get()->keyBy('id')->map(function($item) {
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
            ];
        });
    }

    public function toShortArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
        ];
    }
}

