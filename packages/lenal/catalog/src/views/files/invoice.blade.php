<?php
/**
 * @var \lenal\catalog\DTO\OrderDetailsDTO $order
 */
?>
@extends('catalog::layouts.payment-invoice-file')

@section('content')
    @include('catalog::layouts.payment-invoice-parts.separator')
    @include('catalog::files.parts.order-detail')
    @include('catalog::layouts.payment-invoice-parts.separator')
    @include('catalog::files.parts.products')
    @include('catalog::layouts.payment-invoice-parts.separator')
    @include('catalog::files.parts.total')
    @include('catalog::layouts.payment-invoice-parts.separator')
    <div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:20px;padding: 0 30px;">
        {!! trans('api.mail.invoice.attach_hint_file') !!}
    </div>
    @include('catalog::layouts.payment-invoice-parts.separator')
    @include('catalog::files.parts.payment-customer-care', ['imageTitle' => 'customer_care_black'])
@endsection
