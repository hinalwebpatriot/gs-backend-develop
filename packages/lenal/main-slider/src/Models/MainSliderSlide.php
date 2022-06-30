<?php

namespace lenal\MainSlider\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MainSliderSlide extends Model
{
    use HasTranslations;

    public $timestamps = false;

    public $translatable = [
        'browse_button_title',
        'browse_button_link',
        'detail_button_title',
        'detail_button_link',
        'alt'
    ];

    protected $fillable = [
        'id',
        'title',
        'image',
        'undercover',
        'undercover_video',
        'youtube_code',
        'slider_text',
        'bg_color',
        'browse_button_title',
        'browse_button_link',
        'detail_button_title',
        'detail_button_link',
        'sort'
    ];
}
