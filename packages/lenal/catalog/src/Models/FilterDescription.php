<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FilterDescription extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $fillable = ['slug', 'product_feed', 'video_link'];

    public $translatable = ['video_link'];
}
