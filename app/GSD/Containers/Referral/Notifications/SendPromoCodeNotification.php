<?php


namespace GSD\Containers\Referral\Notifications;


use GSD\Containers\Referral\Mails\PromoCodeMail;
use GSD\Ship\Parents\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class SendPromoCodeNotification
 * @package GSD\Containers\Referral\Notifications
 */
class SendPromoCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string  $sender_first_name;
    private string  $sender_last_name;
    private ?string $comment;

    public function __construct(string $sender_first_name, string $sender_last_name, ?string $comment)
    {
        $this->sender_first_name = $sender_first_name;
        $this->sender_last_name = $sender_last_name;
        $this->comment = $comment;
    }

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
        return new PromoCodeMail($notifiable, $this->sender_first_name, $this->sender_last_name, $this->comment);
    }
}