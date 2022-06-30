<?php

namespace lenal\seo\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $engagement_off
 */
class Meta extends Model
{
    use HasTranslations;

    protected $table = 'seo_meta';
    public $translatable = ['title', 'description', 'keywords', 'h1', 'seo_text'];
}
