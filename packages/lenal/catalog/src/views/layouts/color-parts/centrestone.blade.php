<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
       cellpadding="0" cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:15px;padding-bottom:0px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td class="t-emailBlock t-emailAlignCenter" valign="middle"
                                    style="text-align: left; padding-right: 10px; width: 30%;"></td>
                                <td class="t-emailBlock t-emailBlockPadding t-emailAlignCenter"
                                    valign="middle" style="text-align: left; padding: 0 10px; width: 40%;">
                                    <img width="300" style="display: block; margin: 0 auto; width: 300px;"
                                         src="{{ $imageStorage->url('email/centrestone_logo.gif') }}">
                                </td>
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