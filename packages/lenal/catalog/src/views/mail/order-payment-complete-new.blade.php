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
    @include('catalog::mail.payment-parts.payment-method')
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::mail.parts.customer-detail')
    @include('catalog::layouts.payment-parts.separator')
    @include('catalog::layouts.parts.payment-customer-care', ['imageTitle' => 'customer_care_black'])
@endsection