<?php

namespace lenal\blog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BlogCategory extends Model
{
    use HasTranslations;

    public $timestamps = false;

    public $translatable = ['title'];

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
