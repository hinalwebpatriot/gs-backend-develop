<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $diamond_id
 * @property integer $ring_id
 * @property string $ring_size
 * @property string $engraving
 * @property string $engraving_font
 */

class CompleteRing extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'diamond_id', 'ring_id', 'ring_size', 'engraving', 'engraving_font'];

}
