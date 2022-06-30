<?php

namespace GSD\Containers\Subscribe\Models;

use Carbon\Carbon;
use GSD\Containers\Subscribe\Enums\TypeEnum;
use GSD\Ship\Parents\Models\Model;

/**
 * Class Subscribe
 * @package GSD\Containers\Subscribe\Models
 *
 * @property int    $id
 * @property string $email
 * @property array  $type
 * @property string $gender
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Subscribe extends Model
{
    protected $table    = 'subscribes';
    protected $casts    = ['type' => 'array'];
    protected $fillable = ['email', 'type', 'gender'];
}
