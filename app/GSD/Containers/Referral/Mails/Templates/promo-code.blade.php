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
    {{ $recipient }} just gave you a surprise gift at GS Diamonds!
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
                                             src="{{ $imageStorage->url('email/you_have_been_referr.gif') }}"
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
                                            <p style="text-align: left;">Dear {{ $recipient }},</p><br/>
                                            <p style="text-align: left;">Your friend {{ $sender }} wants to send you a
                                                complimentary surprise gift from GS Diamonds!</p>
                                            @if ($comment)
                                                <br/>
                                                <p style="text-align: left;">This is what your friend wrote:</p><br/>
                                                <p style="text-align: left; font-size: 16px;">
                                                    "<em>{{ $comment }}</em>"
                                                </p>
                                            @endif
                                            <br/>
                                            <p style="text-align: left;">Please use this promo code on checkout
                                                or in-store to redeem an amazing discount on your first
                                                purchase!</p>
                                            <br/>
                                            <div style="margin-right: auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:2;">
                                                <ul>
                                                    <li>save A$100 on your purchase of A$3000 or more</li>
                                                    <li>save A$150 on your purchase of A$6000 or more</li>
                                                    <li>save A$200 on your purchase of A$10000 or more</li>
                                                    <li>save A$250 on your purchase of A$15000 or more</li>
                                                    <li>save A$300 on your purchase of A$20000 or more</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div style="margin: 30px auto; text-align:center;">
                                            <div style="display: inline-block;">
                                            <!--[if mso]>
                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
                                                             xmlns:w="urn:schemas-microsoft-com:office:word"
                                                             href="https://www.gsdiamonds.com.au"
                                                             style="height:58px;v-text-anchor:middle;mso-wrap-style:none;mso-position-horizontal:center;"
                                                             strokecolor="#ac915f">
                                                <w:anchorlock/>
                                                <center style="text-decoration: none; padding: 17px 36px; font-size: 17px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ac915f;">
                                                {{ $code }}
                                                    </center>
                                                    </v:roundrect> <![endif]--> <!--[if !mso]--> <a
                                                        style="display: table-cell; text-decoration: none; padding: 17px 36px; font-size: 17px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ac915f; border:2px solid #ac915f; "
                                                        href="https://www.gsdiamonds.com.au"> {{ $code }} </a>
                                                <!--[endif]-->
                                            </div>
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
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                    <tr>
                        <td style="padding-left:30px;padding-right:30px;">
                            <table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; padding: 0 0 0;">
                                        <div style="margin-right: auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:12px;line-height:1.5;max-width:550px;">
                                            <strong>TERMS AND CONDITIONS:</strong><br/>This offer valid for new
                                            customers only. The promo code can be used only at the first
                                            purchase. The minimum spend is A$3,000. Each promo code is unique
                                            and can be used only once. Offer can be combined with other
                                            discounts. Promotional discounts applied to an order are only valid
                                            for the item purchased and will not be applied to future purchases
                                            or exchanges. By accepting this offer you agree to the Terms and
                                            Conditions and Privacy Policy.
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
                <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                    <tr>
                        <td style="padding-top:0px;padding-left:30px;padding-right:30px;">
                            <table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; padding: 0 0 0;">
                                        <div style="margin-right: auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:12px;line-height:1.5;max-width:550px;">
                                            <p style="text-align: left;"><strong>PLEASE NOTE</strong>: We
                                                respect your privacy. Under no circumstances will GS Diamonds
                                                use your personal information for any purpose other than
                                                informing you about referral discount.</p></div>
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