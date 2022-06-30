<?php

namespace lenal\additional_content\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuDropdownContent extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $timestamps = false;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('menu_dropdowns')
            ->useDisk(config('filesystems.cloud'));
    }
}
