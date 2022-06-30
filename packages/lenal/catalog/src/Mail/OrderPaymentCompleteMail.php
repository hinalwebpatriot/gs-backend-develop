<?php

namespace lenal\catalog\Mail;

use App\Helpers\Tools;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use lenal\catalog\DTO\OrderDetailsDTO;

class OrderPaymentCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = OrderDetailsDTO::make($order);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('api.mail.order_payment_complete.subject'))
            ->view('catalog::mail.order-payment-complete-new', [
                'order' => $this->order,
                'imageStorage' => Tools::defaultStorage()
            ]);
    }
}
