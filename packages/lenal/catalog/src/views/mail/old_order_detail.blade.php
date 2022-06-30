<?php
/**
 * @deprecated using order-new
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\Models\Order $order
 */

$discount = $order->getDiscount();
?>
@extends('catalog::layouts.mail')

@section('header')
    Thanks for your order, {{ $order->first_name }}
@endsection

@section('description')
    The order is confirmed and is currently processing.<br/>You will
    receive an invoice within the next 24 hours.
@endsection

@section('content')

    <table id="rec203525033" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="322">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin203525033" class="r"
                       style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                                <tr>
                                    <td style="width:100%; text-align: center;">
                                        <img width="540" align="center" style="width: 100%; height: auto;"
                                             src="{{ $imageStorage->url('mail/tild3839-6363-4965-a534-333563363132__order_and_payment_de.png') }}"
                                             imgfield="img"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table id="rec217678805" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="636">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin217678805" class="r"
                       style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                            @foreach ($order->cartItems as $item)
                                @if (!$item->product)
                                    @continue
                                @endif
                                <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                       style="table-layout: fixed;">
                                    <tr>
                                        <td class="t-emailBlock" valign="top" style="width: 200px;">
                                            <a href="{{ config('app.frontend_url').'/'.$item->product->getUri() }}">
                                                <img width="200" style="display: block; width: 100% !important;"
                                                     src="{{ $item->product->previewImageUrl() }}">
                                            </a>
                                        </td>
                                        <td class="t-emailBlock t-emailBlockPadding" valign="top"
                                            style="padding-left: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                                                <tr>
                                                    <td style="text-align: left; padding-bottom: 5px;">
                                                        <a href="{{ config('app.frontend_url').'/'.$item->product->getUri() }}"
                                                           style="text-decoration: none; color: #222222;">
                                                            <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;line-height:1.2;">
                                                                {{ $item->product->title }}
                                                            </div>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#777777;font-size:14px;line-height:1.4;">
                                                        @isset($item->product->metal)
                                                            <p style="text-align: left;">Metal: {{ $item->product->metal->title }}</p>
                                                        @endisset

                                                        @isset($item->product->band_width)
                                                            <p style="text-align: left;">Band Width: {{ $item->product->band_width }} mm</p>
                                                        @endisset

                                                        @if (!empty($item->product->carat))
                                                            <p style="text-align: left;">Carat: {{ $item->product->carat }} </p>
                                                        @endisset

                                                        @if (!empty($item->product->color))
                                                            <p style="text-align: left;">Color: {{ $item->product->color->title}} </p>
                                                        @endisset

                                                        @isset($item->product->shape)
                                                            <p style="text-align: left;">Shape: {{ $item->product->shape->title }} </p>
                                                        @endisset

                                                        @if ($item->size)
                                                            <p style="text-align: left;">Ring size: {{ $item->size->getTitle() }}</p>
                                                        @elseif ($item->size_slug)
                                                                <p style="text-align: left;">Ring size: {{ $item->size_slug }}</p>
                                                        @endif

                                                        @if ($item->engraving)
                                                            <p style="text-align: left;">Engraving: {{ $item->engraving }}</p>
                                                            <p style="text-align: left;">Engraving Font: {{ $item->engraving_font }}</p>
                                                        @endif

                                                        <p style="text-align: left;">{{ trans('api.mail.order_details.sku', ['sku' => $item->product->slug]) }}</p>
                                                    </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 12px;">
                                                        <table border="0" cellpadding="0" cellspacing="0"
                                                               align="left" style="margin-left: 0;">
                                                            <tr>
                                                                <td>
                                                                    <!--[if mso]> <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://www.gsdiamonds.com.au/Ariel-Side-Stones-Diamond-Engagement-Ring_3110_e" style="height:32px;v-text-anchor:middle;width:100%;" strokecolor="#000000" > <w:anchorlock/> <center style="text-decoration: none; padding: 6px 12px; font-size: 12px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#000000;"> View on website &gt; </center> </v:roundrect> <![endif]-->
                                                                    <!--[if !mso]-->
                                                                    <a style="display: table-cell; text-decoration: none; padding: 6px 12px; font-size: 12px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#000000; "
                                                                       href="{{ config('app.frontend_url').'/'.$item->product->getUri() }}">
                                                                        View on website &gt;
                                                                    </a> <!--[endif]-->
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                            </table>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="height: 20px;"></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="height: 20px;"></td>
                                    </tr>
                                </table>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table id="rec237173067" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="329">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin237173067" class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 0 0 0;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                            Please note: Prior to the order processing, we will confirm
                                            availability and inspect the diamond to ensure its quality.
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

    @include('catalog::mail.components.separator')

    <table id="rec217713702" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="622">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin217713702" class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="" valign="middle"
                                        style="text-align: left; padding-right: 10px; width: 50%;">
                                        <a style="text-decoration: none; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;"
                                           href="#">
                                            {{ trans('api.mail.order_details.subtotal') }}:<br/>
                                            @if ($discount > 0)
                                                Discount:<br/>
                                                Subtotal with discount:<br/>
                                            @endif

                                            GST:<br/>

                                            @if ($order->promocode)
                                                {{ trans('api.mail.order_details.promocode') }}:<br>
                                            @endif
                                            Shipping:<br/><br/>


                                            <strong>Order Total:</strong>
                                        </a>
                                    </td>
                                    <td class="" valign="middle"
                                        style="text-align: right; padding-left: 10px; width: 50%;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;">
                                            {{ trans("api.currency_format.$order->currency", ['sum' => $order->subtotal + $discount]) }}<br/>

                                            @if ($discount > 0)
                                            -{{ trans("api.currency_format.$order->currency", ['sum' => $discount]) }}<br/>
                                            {{ trans("api.currency_format.$order->currency", ['sum' => $order->subtotal]) }}<br/>
                                            @endif

                                            {{ trans("api.currency_format.$order->currency", ['sum' => $order->tax]) }}<br/>

                                            @if ($order->promocode)
                                                {{ $order->promocode->code }}<br>
                                            @endif

                                            {{ trans("api.currency_format.$order->currency", ['sum' => $order->shipping]) }}<br/><br/>
                                            <strong>{{ trans("api.currency_format.$order->currency", ['sum' => $order->including_tax]) }}</strong>
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
    <table id="rec217697870" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="329">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin217697870" class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: center; padding: 0 0 0;">
                                        <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                            You will receive an invoice within one business day. If you have not
                                            received the invoice please contact us on <a
                                                    href="mailto:callcentre@gsdiamonds.com.au"
                                                    style="color: rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); box-shadow: none;">callcentre@gsdiamonds.com.au</a>
                                            or <a href="tel:1300181294"
                                                  style="color: rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); box-shadow: none;">1300
                                                181 294</a>.
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

    @include('catalog::mail.components.separator')

    <table id="rec203531381" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="326">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin203531381" class="r"
                       style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="table-layout: fixed;">
                                <tr>
                                    <td class="t-emailBlock " valign="top"
                                        style="width: 50%; padding-right: 10px; ">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                                            <tr>
                                                <td style="text-align: center; padding-top: 14px;">
                                                    <div style="margin: 0 auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:1.2;">
                                                        Order Number: {{ $order->id }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="t-emailBlock t-emailBlockPadding30" valign="top"
                                        style="width: 50%; padding-left: 10px; ">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                                            <tr>
                                                <td style="text-align: center; padding-top: 14px;">
                                                    <div style="margin: 0 auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:1.2;">
                                                        Order Date: {{ $order->created_at->format('d M Y') }}
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
            </td>
        </tr>
    </table>
    <table id="rec203532333" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="326">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin203532333" class="r"
                       style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="table-layout: fixed;">
                                <tr>
                                    <td class="t-emailBlock " valign="top"
                                        style="width: 50%; padding-right: 10px; ">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                                            <tr>
                                                <td style="text-align: center; padding-top: 14px;">
                                                    <div style="margin: 0 auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:1.2;">
                                                        @if ($order->showroom)
                                                            {{ trans('api.mail.order_details.shipping_showroom') }}:<br/><br/>
                                                            {{ $order->first_name }} {{ $order->last_name }},<br/>
                                                            {{ $order->showroom->geo_title }},<br/>
                                                            {{ $order->showroom->address }},<br/>
                                                            {{ $order->showroom->country->title }}
                                                        @else
                                                            Shipping Address:<br/><br/>
                                                            {{ $order->first_name }} {{ $order->last_name }}<br/>
                                                            {{ $order->address }}<br/>
                                                            {{ $order->town_city }}, {{ $order->state }}, {{ $order->zip_postal_code }}, {{ $order->country }}<br/>
                                                            T: {{ $order->phone_number }}<br/>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="t-emailBlock t-emailBlockPadding30" valign="top"
                                        style="width: 50%; padding-left: 10px; ">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                                            <tr>
                                                <td style="text-align: center; padding-top: 14px;">
                                                    <div style="margin: 0 auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:1.2;">
                                                        Shipping Method:<br/><br/>
                                                        {{ $order->paySystem->name ?? '' }}<br/>
                                                        7-10 Business Days
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
            </td>
        </tr>
    </table>

    @include('catalog::mail.components.separator')

    <table id="rec215604347" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="322">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin215604347" class="r"
                       style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:15px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                                <tr>
                                    <td style="width:100%; text-align: center;"><img width="540" align="center"
                                                                                     style="width: 100%; height: auto;"
                                                                                     src="{{ $imageStorage->url('mail/tild6537-6336-4231-a562-303361343736__customer_care.png') }}"
                                                                                     imgfield="img"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table id="rec217706996" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="329">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin217706996" class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td style="text-align: left; padding: 0 0 0;">
                                        <div style="margin-right: auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                            <p style="text-align: left;">You have a two-year guarantee against
                                                any manufacturing faults. In the rare instance this happens,
                                                please contact us.</p><br/>
                                            <p style="text-align: left;">We will also give you a 30-day
                                                money-back guarantee on all fully-paid items bought in store and
                                                online. Simply return your jewellery within this time frame for
                                                a refund or exchange.</p><br/>
                                            <p style="text-align: left;">We offer free shipping across Australia
                                                and New Zealand with full insurance while being couriered. Read
                                                more in our <a
                                                        href="https://www.gsdiamonds.com.au/customer-care"
                                                        style="color: rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); box-shadow: none;">Customer
                                                    Care Guide</a>.</p><br/>
                                            <p style="text-align: left;">You can also insure your precious
                                                jewellery with our trusted partners:</p></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table id="rec215591823" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
           cellpadding="0" cellspacing="0" data-record-type="642">
        <tr>
            <td style="padding-left:15px; padding-right:15px; ">
                <table id="recin215591823" class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                       align="center">
                    <tr>
                        <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="t-emailBlock t-emailAlignCenter" valign="middle"
                                        style="text-align: left; padding-right: 10px; width: 30%;"></td>
                                    <td class="t-emailBlock t-emailBlockPadding t-emailAlignCenter"
                                        valign="middle" style="text-align: left; padding: 0 10px; width: 40%;">
                                        <a style="text-decoration: none;" href="#"> <img
                                                    width="300"
                                                    style="display: block; margin: 0 auto; width: 300px;"
                                                    src="{{ $imageStorage->url('mail/tild6539-3237-4337-b861-376139383961__centrestone_logo_v3_.png') }}">
                                        </a></td>
                                    <td class="t-emailBlock t-emailBlockPadding t-emailAlignCenter"
                                        valign="middle"
                                        style="text-align: right; padding-left: 10px; width: 30%;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection