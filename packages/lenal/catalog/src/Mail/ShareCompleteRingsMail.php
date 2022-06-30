<?php

namespace lenal\catalog\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareCompleteRingsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $share_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($share_link)
    {
        $this->share_link = $share_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.share_complete_rings.subject"))
            ->view('catalog::mail.share_complete_rings');
    }
}
