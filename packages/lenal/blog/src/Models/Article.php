<?php

namespace lenal\blog\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use lenal\search\Traits\BlogSearchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Class Article
 *
 * @property string $preview_text
 * @property string $title
 * @property int $view_count
 * @package lenal\blog\Models
 */
class Article extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use Searchable, BlogSearchable {
        BlogSearchable::searchableAs insteadof Searchable;
        BlogSearchable::toSearchableArray insteadof Searchable;
    }

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'preview_text',
        'view_count',
        'priority',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'category_id',
    ];

    /**
     * @var array
     */
    public $translatable = ['title', 'preview_text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailText()
    {
        return $this->hasMany(ArticleDetailBlock::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('main_image')
            ->useDisk(config('filesystems.cloud'));
    }
}
