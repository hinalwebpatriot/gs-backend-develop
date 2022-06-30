<?php


namespace GSD\Containers\Referral\Notifications;


use GSD\Containers\Referral\Mails\PromoCodeMail;
use GSD\Containers\Referral\Mails\PromoCodeUsedMail;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Ship\Parents\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class SendNotifyUsedNotification
 * @package GSD\Containers\Referral\Notifications
 */
class SendNotifyUsedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     *
     * @return mixed
     */
    public function toMail($notifiable)
    {
        return new PromoCodeUsedMail($notifiable);
    }
}