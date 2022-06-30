<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $category
 * @property mixed $label
 */
class ProductField extends Model
{
    use HasTranslations;

    public $timestamps = false;

    /**
     * @var array
     */
    public $translatable = ['label'];

    protected $fillable = [
        'category',
    ];

    public static function findByCategory($category)
    {
        return static::query()->where('category', $category)->get();
    }
}
