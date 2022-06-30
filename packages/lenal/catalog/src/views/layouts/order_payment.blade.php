<?php
/**
 * @deprecated
 */
?>
<div style="font-family: sans-serif">
    <p>{{ trans('api.mail.order_payment.top_text') }}</p>
    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.order_details') }}</p>
    <div style="text-align: left">
        <p>{{ $order->created_at->format('d M Y g:i:s A') }}</p>
        <p>{{ trans('api.mail.order_details.your_order', ['id' => $order->id]) }}</p>
    </div>
    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.payment') }}</p>
    <div style="text-align: left">
        <p>{{ trans('api.mail.order_details.payment_name', ['paysytem_name' => $order->paysystem->name]) }}</p>
        @yield('payment_info')
    </div>
    <div style="text-align: left">
        {!! trans('api.mail.order_base.bottom_text') !!}
    </div>
</div>