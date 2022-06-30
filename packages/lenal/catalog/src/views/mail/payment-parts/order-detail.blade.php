<?php
/**
 * @var \lenal\catalog\DTO\OrderDetailsDTO $order
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
       cellpadding="0" cellspacing="0">
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
                                        {{ $order->createdAt->format('d M Y g:i A') }}
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
                                        Dear {{ $order->firstName }} {{ $order->lastName }}, thanks for
                                        your order! Here you may find detailed information about it.
                                        We sincerely hope to see you again soon!
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
