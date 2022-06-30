<?php


namespace GSD\Containers\Referral\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ReferralPayoutTransaction
 * @package GSD\Containers\Referral\Models
 *
 * @property int    $id
 * @property int    $owner_id
 * @property double $payout
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ReferralPayoutTransaction extends Model
{
    protected $table = 'referral_payout_transactions';

    protected $fillable = ['owner_id', 'payout'];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(ReferralCustomer::class, 'owner_id', 'id');
    }
}