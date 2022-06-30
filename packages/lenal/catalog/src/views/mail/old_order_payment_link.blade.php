<?php
/**
 * @deprecated
 */
?>
@extends('catalog::layouts.order_payment')
@section('payment_info')
    <p><br><a href="{{ $payment_link }}">{{ trans('api.mail.payment_link') }}</a><br><br></p>
@endsection