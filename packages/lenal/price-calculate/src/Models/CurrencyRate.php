<?php

namespace lenal\PriceCalculate\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'from_currency',
        'to_currency',
        'rate',
        'created_at',
        'updated_at',
    ];
}