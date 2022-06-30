<?php

namespace lenal\catalog\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductHintMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( array $requestData)
    {
        $this->content = $requestData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.product_hint.subject"))
            ->view('catalog::mail.product_hint')
            ->with([
                'full_link' => env('FRONTEND_URL').$this->content['link']
            ]);
    }
}
