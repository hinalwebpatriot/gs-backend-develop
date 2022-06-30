<?php

namespace lenal\catalog\Models\Diamonds;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Polish extends Model
{
    use HasTranslations;

    public $timestamps = false;
    protected $table = 'polishes';

    public $translatable = ['title'];

    protected $fillable = [
        'title', 'slug', 'value'
    ];

    public function diamonds()
    {
        return $this->hasMany('lenal\catalog\Models\Diamonds\Diamond');
    }
}

