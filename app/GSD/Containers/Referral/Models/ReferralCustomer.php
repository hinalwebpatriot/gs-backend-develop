<?php


namespace GSD\Containers\Referral\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ReferralCustomers
 * @package GSD\Containers\Referral\Models
 *
 * @property int                       $id
 * @property string                    $email
 * @property string                    $phone
 * @property string                    $first_name
 * @property string                    $last_name
 * @property string                    $balance
 * @property Carbon                    $created_at
 * @property Carbon                    $updated_at
 *
 * @property ReferralPromoCode         $promoCode
 * @property ReferralTransaction       $transactions
 * @property ReferralPayoutTransaction $payouts
 */
class ReferralCustomer extends Model
{
    protected $table = 'referral_customers';

    protected $fillable = ['email', 'phone', 'first_name', 'last_name', 'balance'];

    /**
     * @return HasMany
     */
    public function promoCodes(): HasMany
    {
        return $this->hasMany(ReferralPromoCode::class, 'owner_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ReferralTransaction::class, 'owner_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(ReferralPayoutTransaction::class, 'owner_id', 'id');
    }
}