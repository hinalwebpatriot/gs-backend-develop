<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;

class SharedCompleteRings extends Model
{
    protected $fillable = [
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
