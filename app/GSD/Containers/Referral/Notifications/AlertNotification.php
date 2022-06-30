<?php


namespace GSD\Containers\Referral\Notifications;


use GSD\Containers\Referral\Mails\PromoCodeMail;
use GSD\Ship\Parents\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

/**
 * Class AlertNotification
 * @package GSD\Containers\Referral\Notifications
 */
class AlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private array  $messages;

    public function __construct(array  $messages)
    {
        $this->messages = $messages;
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
        return [TelegramChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return mixed
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content(implode("\n\n", $this->messages));
    }
}