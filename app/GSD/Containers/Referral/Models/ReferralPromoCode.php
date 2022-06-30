<?php


namespace GSD\Containers\Referral\Models;


use Carbon\Carbon;
use GSD\Containers\Referral\Exceptions\NotHasConfirmationException;
use GSD\Containers\Referral\Interfaces\PromoCodeInterface;
use GSD\Containers\Referral\Notifications\SendNotifyUsedNotification;
use GSD\Containers\Referral\Notifications\SendPromoCodeNotification;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * Class ReferralPromocode
 * @package GSD\Containers\Referral\Models\
 *
 * @property int                   $id
 * @property int                   $owner_id
 * @property string                $code
 * @property bool                  $is_used
 * @property string                $recipient_email
 * @property string                $recipient_first_name
 * @property string                $recipient_last_name
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 *
 * @property-read ReferralCustomer $owner
 */
class ReferralPromoCode extends Model implements PromoCodeInterface
{
    use Notifiable;

    protected $table = 'referral_promocodes';

    protected $fillable = [
        'owner_id', 'code', 'is_used',
        'recipient_email', 'recipient_first_name', 'recipient_last_name'];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(ReferralCustomer::class, 'owner_id', 'id');
    }


    /**
     * Отправляет промокод на почту получателю
     *
     * @param  string  $sender_first_name
     * @param  string  $sender_last_name
     * @param  string|null  $comment
     */
    public function sendPromoCodeToRecipient(string $sender_first_name, string $sender_last_name, ?string $comment)
    {
        $this->notify(new SendPromoCodeNotification($sender_first_name, $sender_last_name, $comment));
    }

    /**
     * Отправляет сообщение об использовании промокода на почту держателя
     */
    public function sendNotifyUsed()
    {
        $this->notify(new SendNotifyUsedNotification());
    }

    /**
     * Емеил получателя для отправки кода
     *
     * @param $notification
     *
     * @return string
     */
    public function routeNotificationForMail($notification): string
    {
        return $this->recipient_email;
    }

    /**
     * Возвращает строку промокода
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public function isRelevant(): bool
    {
        return !$this->is_used;
    }

    /**
     * @inheritdoc
     */
    public function isActive(): bool
    {
        return !$this->is_used;
    }

    /**
     * @param $confirmCode
     *
     * @return bool
     * @throws NotHasConfirmationException
     */
    public function confirmation($confirmCode): bool
    {
        throw new NotHasConfirmationException();
    }
}