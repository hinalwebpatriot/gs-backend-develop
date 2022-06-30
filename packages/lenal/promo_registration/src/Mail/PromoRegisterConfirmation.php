<?php

namespace lenal\promo_registration\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PromoRegisterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this1
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.common-confirm-code-subject"))
            ->view('promo_registration::mail.code-confirmation', ['code' => $this->code]);
    }
}
