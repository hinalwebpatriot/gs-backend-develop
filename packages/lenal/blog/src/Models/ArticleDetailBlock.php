<?php

namespace lenal\blog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class ArticleDetailBlock extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public $timestamps = false;

    public $translatable = ['title', 'text', 'video'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('main_image_detail')
            ->useDisk(config('filesystems.cloud'));
    }
}
