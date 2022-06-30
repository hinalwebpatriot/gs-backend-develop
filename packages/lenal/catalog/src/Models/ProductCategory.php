<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property integer $is_suggested
 * @property string $alt
 */
class ProductCategory extends Model
{
    use HasTranslations;

    public $timestamps = false;

    public $translatable = ['name', 'slug', 'alt'];

}
