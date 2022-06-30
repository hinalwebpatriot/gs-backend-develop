<?php

namespace lenal\catalog\Mail;

use App\Helpers\Tools;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use lenal\catalog\DTO\OrderDetailsDTO;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdfAttach, $order)
    {
        $this->invoice = $pdfAttach;
        $this->order = $order;
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
            ->view('catalog::mail.order-invoice-new', [
                'order' => $this->order,
                'imageStorage' => Tools::defaultStorage()
            ])
            ->attach($this->invoice, [
                'mime' => 'application/pdf',
            ]);
    }
}
