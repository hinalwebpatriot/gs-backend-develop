<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property string $credentials
 * @property string $type
 */
class Paysystem extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $casts = ['credentials'];

    protected $fillable = ['slug', 'name', 'description', 'logo', 'credentials', 'type'];

    public $translatable = ['description'];

    /**
     * @param string $slug
     * @return Paysystem|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function findBySlug($slug)
    {
        return static::query()->where('slug', $slug)->first();
    }

    public function parseCredentials()
    {
        return json_decode($this->credentials, true);
    }

    public function discountPercent()
    {
        return (float) ($this->parseCredentials()['payment_discount'] ?? 0);
    }
}
