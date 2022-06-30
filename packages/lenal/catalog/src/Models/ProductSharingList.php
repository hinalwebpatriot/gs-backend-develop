<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSharingList extends Model
{
    public $timestamps = false;

    public function items() {
        return $this->hasMany(SharingProduct::class, 'list_id');
    }
}
