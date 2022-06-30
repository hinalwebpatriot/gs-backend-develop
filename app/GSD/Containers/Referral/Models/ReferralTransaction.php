<?php


namespace GSD\Containers\Referral\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ReferralTransaction
 * @package GSD\Containers\Referral\Models
 *
 * @property int                    $id
 * @property int                    $owner_id
 * @property int                    $code_id
 * @property int                    $order_id
 * @property int                    $tower_id
 * @property double                 $order_sum
 * @property double                 $payment
 * @property Carbon                 $approved_at
 * @property Carbon                 $created_at
 * @property Carbon                 $updated_at
 *
 * @property-read bool              $isApproved
 *
 * @property-read ReferralCustomer  $owner
 * @property-read ReferralPromoCode $code
 */
class ReferralTransaction extends Model
{
    protected $table = 'referral_transactions';

    protected $fillable = [
        'owner_id', 'code_id', 'order_id', 'tower_id', 'order_sum', 'payment', 'approved_at'
    ];

    protected $dates = [
        'approved_at'
    ];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(ReferralCustomer::class, 'owner_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function code(): BelongsTo
    {
        return $this->belongsTo(ReferralPromoCode::class, 'code_id', 'id');
    }

    /**
     * Возвращает зачислен ли платеж держателю рефералки
     *
     * @return bool
     */
    public function getIsApprovedAttribute(): bool
    {
        return !!$this->approved_at;
    }
}