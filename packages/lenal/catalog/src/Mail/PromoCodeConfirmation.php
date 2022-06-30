<?php

namespace lenal\catalog\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PromoCodeConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $code;

    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.confirm-code-subject"))
            ->view('catalog::mail.promo_code_confirmation', ['code' => $this->code]);
    }
}
