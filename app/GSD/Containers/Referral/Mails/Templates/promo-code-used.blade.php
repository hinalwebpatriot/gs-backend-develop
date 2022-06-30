<?php
/**
 * @var string  $recipient
 * @var string  $code
 * @var string  $sender
 * @var string  $comment
 * @var Storage $imageStorage
 */

use Illuminate\Support\Facades\Storage;

?>

@extends('catalog::layouts.color-mail')

@section('title')
    Your promo code has been used!
@endsection

@section('content')
    @include('catalog::layouts.color-parts.separator')
    <table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                                <tr>
                                    <td style="width:100%; text-align: center;">
                                        <img width="540" align="center"
                                             style="width: 100%; height: auto;"
                                             src="{{ $imageStorage->url('email/promo_code_used.gif') }}"
                                             imgfield="img">
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
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                    <tr>
                        <td style="padding-top:45px;padding-bottom:15px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; padding: 0 0 0;">
                                        <div style="margin-right: auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:18px;line-height:1.4;">
                                            <p style="text-align: left;">Dear {{ $name }},</p><br/>
                                            <p style="text-align: left;">Great news! Your friend used your promo code! You are on the way to receive your benefits. Once the money-back guarantee period for your friend's purchase is over, you will be able to redeem your gift card.</p>
                                            <br/>
                                            <p style="text-align: left;">No need to worry, we will contact you when your card is ready.</p>
                                            <br/>
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
@endsection