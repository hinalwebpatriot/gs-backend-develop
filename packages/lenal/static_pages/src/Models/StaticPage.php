<?php

namespace lenal\static_pages\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class StaticPage
 *
 * @package lenal\static_pages\Models
 */
class StaticPage extends Model
{
    use HasTranslations;

    public $translatable = [
        'title_translatable',
        'text_translatable'
    ];

    protected $casts = [
        'title_translatable' => 'array',
        'text_translatable' => 'array'
    ];

    protected $fillable = [
        'title_translatable',
        'text_translatable'
    ];
}
