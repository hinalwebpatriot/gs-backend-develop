<?php
/**
 * @var \lenal\catalog\DTO\OrderDetailsDTO $order
 */
?>
@extends('catalog::layouts.payment-email')

@section('title')
    {{ trans("api.mail.order_payment.subject") }}
@endsection

@section('content')
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::mail.payment-parts.order-detail')
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::mail.payment-parts.products')
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::mail.parts.total')
    @include('catalog::layouts.payment-parts.separator')
    <table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="637">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="height:15px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                        {!! trans('api.mail.invoice.attach_hint') !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::layouts.parts.payment-customer-care', ['imageTitle' => 'customer_care_black'])
@endsection
