<?php

namespace lenal\catalog\Models\Rings;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $size
 */
class RingSize extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'slug', 'size',
    ];

    public function engagementRings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\EngagementRing');
    }

    public function weddingRings()
    {
        return $this->hasMany('lenal\catalog\Models\Rings\WeddingRing');
    }

    public static function asArray()
    {
        $result = [];
        $items = RingSize::query()->get()->toArray();

        foreach ($items as $item) {
            $result[(string) $item['slug']] = [
                'id'    => $item['id'],
                'slug'  => $item['slug'],
                'title' => $item['title'],
            ];
        }

        return $result;
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'slug'  => $this->slug,
            'title' => json_decode($this->size, true),
        ];
    }

    public static function bySlug($slug): ?array
    {
        $item = RingSize::query()->where('slug', $slug)->first();
        if ($item) {
            return [
                'id'    => $item['id'],
                'slug'  => $item['slug'],
                'title' => json_decode($item['size'], true),
            ];
        }
        return null;
    }

    public function getTitle()
    {
        return json_decode($this->size)->au ?? '-';
    }
}
