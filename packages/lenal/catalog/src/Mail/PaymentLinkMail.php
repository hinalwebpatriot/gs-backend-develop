<?php

namespace lenal\catalog\Mail;

use App\Helpers\Tools;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $payment_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $payment_link)
    {
        $this->order = $order;
        $this->payment_link = $payment_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.order_payment.subject"))
            ->view(
                'catalog::mail.order-payment-link-new',
                [
                    'order' => $this->order,
                    'payment_link' => $this->payment_link,
                    'imageStorage' => Tools::defaultStorage()
                ]
            );
    }
}
