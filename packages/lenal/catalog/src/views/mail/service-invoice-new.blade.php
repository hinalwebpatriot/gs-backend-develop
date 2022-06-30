@extends('catalog::layouts.color-mail')

@section('title')
    {{ trans("api.mail.invoice.subject") }}
@endsection

@section('content')
    @include('catalog::mail.service-invoice-parts.your-order-is-ready')
    @include('catalog::layouts.color-parts.separator')
    @include('catalog::mail.service-invoice-parts.review-my-order')
@endsection