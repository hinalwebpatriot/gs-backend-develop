<?php

namespace lenal\MainSlider\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Models
 */
class MainSlider extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'title'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function slides()
    {
        return $this->belongsToMany(MainSliderSlide::class)->orderBy('sort');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(MainSliderVideo::class);
    }
}
