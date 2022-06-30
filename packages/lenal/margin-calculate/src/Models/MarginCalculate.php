<?php

namespace lenal\MarginCalculate\Models;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Manufacturer;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Models
 */
class MarginCalculate extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'margins';

    /**
     * @var array
     */
    protected $appends = [
        'carat_range',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'is_round',
        'carat_min',
        'carat_max',
        'clarity_id',
        'color_id',
        'manufacturer_id',
        'margin',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'manufacturer_id',
        'clarity_id',
        'color_id',
    ];

    /**
     * @return string
     */
    public function getCaratRangeAttribute()
    {
        return "$this->carat_min - $this->carat_max";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
         return $this->belongsTo(Manufacturer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clarity()
    {
        return $this->belongsTo(Clarity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
