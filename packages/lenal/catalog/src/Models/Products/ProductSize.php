<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Rings\RingSize;

/**
 * @property integer $id
 * @property number $slug
 * @property mixed $title
 */
class ProductSize extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'slug', 'title',
    ];

    protected $casts = [
        'title' => 'array'
    ];

    public static function asArray()
    {
        return static::query()->get()->keyBy('id')->map(function(ProductSize $size) {
            return $size->map();
        });
    }

    public function map()
    {
        $title = $this->title[app()->getLocale()] ?? '';
        if (!$title && $this->title) {
            $title = current($this->title);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $title,
        ];
    }
}
