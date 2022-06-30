<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $value
 */
class Status extends Model
{
    const STATUS_PROCESS = 'prossesing';
    const STATUS_PAID = 'paid';

    protected $table = 'status';

    public $timestamps = false;

    protected $casts = ['credentials'];

    protected $fillable = ['slug', 'title', 'id', 'value'];

    /**
     * @param string $value
     * @return static|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function findBySlug($value)
    {
        return static::query()->where('slug', $value)->first();
    }

}
