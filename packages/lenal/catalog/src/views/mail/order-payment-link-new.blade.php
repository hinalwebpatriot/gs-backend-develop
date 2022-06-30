<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\Models\Order         $order
 * @var string                              $payment_link
 */
?>
@extends('catalog::layouts.color-mail')

@section('title')
    {{ trans("api.mail.order_payment.subject") }}
@endsection

@section('content')
    @include('catalog::layouts.color-parts.separator')
    <table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 0 0 0;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:16px;line-height:1.4;text-transform:uppercase;font-weight:bold;">
                                            <p>{{ trans('api.mail.order_payment.top_text') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 0 0 0;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:1.4;text-transform:uppercase;font-weight:bold;">
                                            Order #{{ $order->id }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 0 0 0;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                            {{ $order->created_at->format('d M Y g:i A') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @include('catalog::layouts.color-parts.separator')
    <table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;text-align: center;">
                            <p>{{ trans('api.mail.order_details.payment_name', ['paysytem_name' => $order->paysystem->name]) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                            <!-- Workaround: Calculate border radius for Outlook-->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0"
                                               style="margin: 0 auto;" align="center">
                                            <tr>
                                                <td> <!--[if mso]>
                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
                                                             xmlns:w="urn:schemas-microsoft-com:office:word"
                                                             href="{{ $payment_link }}"
                                                             style="height:46px;v-text-anchor:middle;mso-wrap-style:none;mso-position-horizontal:center;"
                                                             arcsize="9%" stroke="f" fillcolor="#ac915f">
                                                <w:anchorlock/>
                                                <center style="text-decoration: none; padding: 12px 24px; font-size: 14px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ffffff;">
                                                    {{ trans('api.mail.payment_link') }}
                                                        </center>
                                                        </v:roundrect> <![endif]-->
                                                    <!--[if !mso]-->
                                                    <a style="display: table-cell; text-decoration: none; padding: 12px 24px; font-size: 14px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ffffff; border:0px solid ; background-color:#ac915f; border-radius: 3px;"
                                                       href="{{ $payment_link }}">
                                                        {{ trans('api.mail.payment_link') }}
                                                    </a>
                                                    <!--[endif]-->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection