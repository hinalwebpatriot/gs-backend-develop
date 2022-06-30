<?php

namespace lenal\catalog\Services;


use Illuminate\Support\Facades\Mail;
use lenal\catalog\Mail\OrderPaymentCompleteMail;
use lenal\catalog\Models\Order;
use lenal\catalog\Models\Status;

class OrderService
{
    /**
     * @var Order
     */
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function process()
    {
        if ($this->order->isPayed()) {
            throw new \Exception(trans('api.order-already-payed'));
        }

        $this->afterPaid();
    }

    public function afterPaid()
    {
        /** @var Status $status */
        $status = Status::findBySlug(Status::STATUS_PAID);
        $this->order->update(['status' => $status->id, 'is_payed' => true]);

        if ($this->order->invoice) {
            $this->order->invoice->paidStatus();
        }

        if ($this->order->email) {
            Mail::to($this->order->email)->send(new OrderPaymentCompleteMail($this->order));
        }

        Mail::to(config('app.sale_mail'))->send(new OrderPaymentCompleteMail($this->order));
        Mail::to(config('app.team_mail'))->send(new OrderPaymentCompleteMail($this->order));
    }
}