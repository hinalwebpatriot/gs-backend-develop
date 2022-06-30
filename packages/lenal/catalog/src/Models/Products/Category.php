<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $slug
 * @property mixed $name
 */
class Category extends Model
{
    use HasTranslations;

    const ENGAGEMENT = 'engagement';
    const WEDDING = 'wedding';
    const DIAMONDS = 'diamonds';

    public $timestamps = false;

    public $translatable = ['name'];

    public static function asArray()
    {
        return static::query()->get()->keyBy('id')->map(function(Category $category) {
            return $category->map();
        });
    }

    public static function promocodeCategories()
    {
        return static::availableCategories();
    }

    public static function availableCategories()
    {
        return array_merge(static::asArray()->pluck('name', 'slug')->toArray(), [
            self::ENGAGEMENT => 'Engagement rings',
            self::WEDDING => 'Wedding rings',
            self::DIAMONDS => 'Diamonds',
        ]);
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }

    public function getUri()
    {
        return 'jewellery/' . $this->slug;
    }
}
