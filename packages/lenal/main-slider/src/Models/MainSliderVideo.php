<?php

namespace lenal\MainSlider\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class MainSliderVideo
 *
 * @package lenal\MainSlider\Models
 */
class MainSliderVideo extends Model
{
    use HasTranslations;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $translatable = ['youtube_link'];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'youtube_link',
    ];
}
