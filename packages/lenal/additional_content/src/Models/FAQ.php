<?php

namespace lenal\additional_content\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FAQ extends Model
{
    use HasTranslations;

    protected $table = 'faqs';
    public $timestamps = false;

    public $translatable = ['title', 'text'];
}
