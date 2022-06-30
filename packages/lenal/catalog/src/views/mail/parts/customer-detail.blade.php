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
                                                    Order Number: {{ $order->id }}<br/><br/>
                                                    @if ($order->isShowroom)
                                                        {{ trans('api.mail.order_details.shipping_showroom') }}:
                                                        <br/><br/>
                                                        {{ $order->firstName }} {{ $order->lastName }},<br/>
                                                        {{ $order->showroomTitle }},<br/>
                                                        {{ $order->showroomAddress }},<br/>
                                                        {{ $order->showroomCountry }}
                                                    @else
                                                        Shipping Address:<br/><br/>
                                                        {{ $order->firstName }} {{ $order->lastName }}<br/>
                                                        {{ $order->address }}<br/>
                                                        {{ $order->townCity }}, {{ $order->state }}
                                                        , {{ $order->zip }}, {{ $order->country }}<br/>
                                                        T: {{ $order->phone }}<br/>
                                                        @if($order->phoneAdditional)
                                                            T: {{ $order->phoneAdditional }}<br/>
                                                        @endif
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
                                                    Order Date: {{ $order->createdAt->format('d F Y') }}<br/><br/>

                                                    Shipping Method:<br/><br/>
                                                    Fedex<br/>
                                                    5-6 business weeks
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

