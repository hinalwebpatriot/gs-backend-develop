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
            <table class="r" style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                            <tr>
                                <td style="width:100%; text-align: center;">
                                    <img width="540" align="center" style="width: 100%; height: auto;"
                                         src="{{ $imageStorage->url('email/order_and_payment_black.gif') }}"
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

<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;" cellpadding="0"
       cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r"
                   style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                        @foreach($order->items as $item)
                            @include('catalog::mail.payment-parts.product', ['item' => $item])
                            @if (!$loop->last)
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="height: 20px;"></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="height: 20px;"></td>
                                    </tr>
                                </table>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
