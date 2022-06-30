<?php

namespace lenal\AppSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Location extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    public $timestamps = false;

}
