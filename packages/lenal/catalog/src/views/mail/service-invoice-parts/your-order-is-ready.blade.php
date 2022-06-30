<?php
/**
 * @var \lenal\catalog\Models\Invoice       $invoice
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
       cellpadding="0" cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r" style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:0px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                            <tr>
                                <td style="width:100%; text-align: center;">
                                    <img width="540" align="center" style="width: 100%; height: auto;"
                                         src="{{ $imageStorage->url('email/your_order_is_ready.gif') }}"
                                         imgfield="img">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;" align="center">
                <tr>
                    <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
                            <tr>
                                <td style="text-align: center; padding: 0 0 0;">
                                    <div style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:1.4;">
                                        Please review detailed information by following the link below.
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
