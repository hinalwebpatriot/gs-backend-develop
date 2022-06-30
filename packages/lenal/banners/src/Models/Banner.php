<?php

namespace lenal\banners\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = ['image', 'page'];


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('subscribe-form')
            ->useDisk(config('filesystems.cloud'));
    }
}
