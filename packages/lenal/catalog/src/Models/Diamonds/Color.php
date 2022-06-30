<?php

namespace lenal\catalog\Models\Diamonds;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug
 */
class Color extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'slug', 'value'
    ];

    public function diamonds()
    {
        return $this->hasMany('lenal\catalog\Models\Diamonds\Diamond');
    }
}

