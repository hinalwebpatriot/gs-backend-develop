<?php

namespace lenal\ShowRooms\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $main_geo_title
 * @property string $geo_title
 * @property float $geo_position_lat
 * @property float $geo_position_lng
 * @property string $address
 * @property string $image
 * @property string $youtube_link
 * @property string $phone
 * @property string $phone_description
 * @property string $schedule
 * @property string $description
 * @property integer $country_id
 * @property string $expert_title
 * @property string $expert_text
 * @property string $expert_list_1
 * @property string $expert_list_2
 * @property string $expert_list_3
 * @property string $expert_name
 * @property string $expert_email
 * @property string $expert_photo
 * @property string $desc_start
 * @property string $desc_middle
 * @property string $desc_end
 * @property string $tab_title
 */
class ShowRoom extends Model
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
        'main_geo_title',
        'geo_title',
        'address',
        'youtube_link',
        'phone_description',
        'schedule',
        'description',
        'expert_title',
        'expert_text',
        'expert_list_1',
        'expert_list_2',
        'expert_list_3',
        'expert_name',
        'expert_photo',
        'tab_title',
        'desc_start',
        'desc_middle',
        'desc_end',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'main_geo_title',
        'geo_title',
        'geo_position_lat',
        'geo_position_lng',
        'address',
        'image',
        'youtube_link',
        'phone',
        'phone_description',
        'schedule',
        'description',
        'is_active',
        'tab_title',
        'desc_start',
        'desc_middle',
        'desc_end',
    ];

    protected $casts = ['expert_email' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(ShowRoomCountry::class);
    }
}
