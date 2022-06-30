<?php

namespace lenal\catalog\Mail;

use Illuminate\Mail\Mailable;
use lenal\catalog\Models\Promocode;

class PromocodeMail extends Mailable
{
    /**
     * @var Promocode
     */
    public $promocode;

    public function __construct(Promocode $promocode)
    {
        $this->promocode = $promocode;
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
            ->view('catalog::mail.promo', ['promocode' => $this->promocode]);
    }
}
