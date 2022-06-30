<?php

namespace lenal\subscribe\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $casts = [
        'type' => 'array'
    ];

    protected $fillable = [
        'email',
        'type',
        'gender'
    ];
}
