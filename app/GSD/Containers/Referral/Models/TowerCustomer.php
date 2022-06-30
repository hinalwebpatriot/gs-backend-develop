<?php


namespace GSD\Containers\Referral\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;

/**
 * Class TowerCustomer
 * @package GSD\Containers\Referral\Models
 *
 * @property int    $id
 * @property string $email
 * @property string $phone
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $last_sent_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TowerCustomer extends Model
{
    protected $dates = ['last_sent_at'];

    protected $table = 'tower_customers';

    protected $fillable = ['email', 'phone', 'first_name', 'last_name', 'last_sent_at'];
}