<?php

namespace lenal\promo_registration\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property mixed $header
 * @property mixed $preview
 * @property string $photo
 * @property int $is_active
 * @property int $discount_percent
 * @property int $discount_value
 *
 */
class PromoRegistrationText extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $table = 'promo_registration_text';

    public $translatable = [
        'header',
        'preview'
    ];
    protected $casts = [
        'header' => 'array',
        'preview' => 'array'
    ];
    public $timestamps = false;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('promo_register')
            ->useDisk(config('filesystems.cloud'))
            ->singleFile();
    }

    public function hasDiscount()
    {
        return $this->discount_value > 0 || $this->discount_percent > 0;
    }
}
