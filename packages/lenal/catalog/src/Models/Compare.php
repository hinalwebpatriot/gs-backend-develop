<?php

namespace lenal\catalog\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    public function product()
    {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
