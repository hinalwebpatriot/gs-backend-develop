<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\DTO\OrderDetailsDTO  $order
 */
?>

<table width="100%">
    <tr>
        <td class="" valign="middle"
            style="text-align: left; width: 50%;">
            <div style="text-decoration: none; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px; padding: 0 30px;">
                Subtotal:<br/>
                @if ($order->discount > 0)
                    Discount:<br/>
                    Subtotal with discount:<br/>
                @endif
                @if ($order->hasPromoCode)
                    Promo-code:<br>
                @endif
                GST:<br/>
                Shipping:<br/><br/>
                <strong>Order Total:</strong>
            </div>
        </td>
        <td class="" valign="middle"
            style="text-align: right; padding-left: 10px; width: 50%;">
            <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px; padding: 0 30px;">
                {{ trans("api.currency_format.$order->currency", [
                    'sum' => number_format($order->subtotal + $order->discount)
                ]) }}<br/>
                @if ($order->discount > 0)
                    -{{ trans("api.currency_format.$order->currency", [
                                                'sum' => number_format($order->discount)
                                            ]) }}<br/>
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
