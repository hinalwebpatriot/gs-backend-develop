<?php

namespace lenal\catalog\Models\Diamonds;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property bool $custom_made
 */
class Manufacturer extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'custom_made'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function diamonds()
    {
        return $this->hasMany('lenal\catalog\Models\Diamonds\Diamond');
    }
}

