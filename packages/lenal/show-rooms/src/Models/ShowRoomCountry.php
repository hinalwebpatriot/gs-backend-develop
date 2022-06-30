<?php

namespace lenal\ShowRooms\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Models
 */
class ShowRoomCountry extends Model
{
    use HasTranslations;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $translatable = [
        'title',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'title',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function showRooms()
    {
        return $this->hasMany(ShowRoom::class, 'country_id');
    }
}
