<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHint extends Model
{
    protected $fillable = [
        'email',
        'product_id',
        'product_type'
    ];

    public function product()
    {
        return $this->morphTo();
    }
}
