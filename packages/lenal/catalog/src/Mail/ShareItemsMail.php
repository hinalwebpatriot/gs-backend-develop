<?php

namespace lenal\catalog\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareItemsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shareListType;
    public $shareLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shareListType, $shareLink)
    {
        $this->shareListType = trans("api.mail.share.$shareListType");
        $this->shareLink = $shareLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans("api.mail.share.subject", ['share_list_type' => $this->shareListType]))
            ->view('catalog::mail.share_items');
    }
}
