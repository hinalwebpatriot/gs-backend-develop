<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property mixed $title
 * @property mixed $description
 * @property mixed $story_title
 * @property mixed $story_video
 * @property mixed $story_text
 * @property string $slug
 */
class Brand extends Model
{
    use HasTranslations;

    public $timestamps = false;

    public $translatable = ['title', 'description', 'story_title', 'story_video', 'story_text'];

    protected $fillable = [
        'title', 'slug', 'description'
    ];

    public static function asArray()
    {
        return static::query()->get()->keyBy('id')->map(function(Brand $brand) {
            return $brand->map();
        });
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
        ];
    }

    public static function retrieveActiveBrands()
    {
        return static::query()
            ->join('products', 'products.brand_id', '=', 'brands.id')
            ->groupBy(['brands.id'])
            ->get()
            ->pluck('title', 'id')
            ->toArray();
    }

    public function products()
    {
        return $this->hasMany('lenal\catalog\Models\Products\Product', 'brand_id');
    }
}
