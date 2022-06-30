<?php

namespace lenal\PriceCalculate\Models;

use Illuminate\Database\Eloquent\Model;

class CountryVat extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'country_code',
        'vat',
        'created_at',
        'updated_at',
    ];
}