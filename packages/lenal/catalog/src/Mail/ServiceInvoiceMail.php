<?php

namespace lenal\catalog\Mail;

use App\Helpers\Tools;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use lenal\catalog\Models\Invoice;

class ServiceInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @param  Invoice  $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans("api.mail.order_payment.subject"))
            ->view('catalog::mail.service-invoice-new', [
                'invoice'      => $this->invoice,
                'imageStorage' => Tools::defaultStorage()
            ]);
    }
}
