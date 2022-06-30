<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\DTO\OrderDetailsDTO  $order
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;" cellpadding="0"
       cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                <tr>
                    <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td class="" valign="middle"
                                    style="text-align: left; padding-right: 10px; width: 50%;">
                                    <div style="text-decoration: none; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;">
                                        Subtotal:<br/>
                                        @if ($order->referralDiscount > 0)
                                            Referral Discount:<br/>
                                        @endif
                                        @if ($order->discount > 0)
                                            Discount:<br/>
                                        @endif
                                        @if ($order->referralDiscount > 0 || $order->discount > 0)
                                            Subtotal with discount:<br/>
                                        @endif
                                        @if ($order->hasPromoCode)
                                            Promo-code:<br/>
                                        @endif
                                        GST:<br/>
                                        Shipping:<br/><br/>
                                        <strong>Order Total:</strong>
                                    </div>
                                </td>
                                <td class="" valign="middle"
                                    style="text-align: right; padding-left: 10px; width: 50%;">
                                    <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;">
                                        {{ trans("api.currency_format.$order->currency", [
                                            'sum' => number_format($order->subtotal + $order->discount + $order->referralDiscount)
                                        ]) }}<br/>
                                        @if ($order->referralDiscount > 0)
                                            -{{ trans("api.currency_format.$order->currency", [
                                                'sum' => number_format($order->referralDiscount)
                                            ]) }}<br/>
                                        @endif
                                        @if ($order->discount > 0)
                                            -{{ trans("api.currency_format.$order->currency", [
                                                'sum' => number_format($order->discount)
                                            ]) }}<br/>
                                        @endif
                                        @if ($order->discount > 0 || $order->referralDiscount > 0)
                                            {{ trans("api.currency_format.$order->currency", [
                                                'sum' => number_format($order->subtotal)
                                            ]) }}<br/>
                                        @endif
                                        @if ($order->hasPromoCode)
                                            {{ $order->promoCode }}<br>
                                        @endif
                                        {{ trans("api.currency_format.$order->currency", [
                                            'sum' => number_format($order->gst)
                                        ]) }}<br/>
                                        {{ trans("api.currency_format.$order->currency", [
                                            'sum' => number_format($order->shipping)
                                        ]) }}<br/><br/>
                                        <strong>
                                            {{ trans("api.currency_format.$order->currency", [
                                                'sum' => number_format($order->total)
                                            ]) }}
                                        </strong>
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
