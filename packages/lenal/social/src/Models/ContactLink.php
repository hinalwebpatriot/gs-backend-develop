<?php

namespace lenal\social\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLink extends Model
{
    public $timestamps = false;

    protected $casts = [
        'value' => 'array'
    ];
}
