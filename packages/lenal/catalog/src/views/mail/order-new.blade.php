<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\DTO\OrderDetailsDTO  $order
 */
?>
@extends('catalog::layouts.color-mail')

@section('title')
    {{ trans("api.mail.order_base.subject") }}
@endsection

@section('content')
    @include('catalog::mail.order-parts.thanks-for-order')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::mail.order-parts.products')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::mail.parts.total')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::mail.order-parts.payment-button')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::mail.parts.customer-detail')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::layouts.parts.customer-care', ['imageTitle' => 'customer_care'])
    @include('catalog::layouts.color-parts.centrestone')
@endsection