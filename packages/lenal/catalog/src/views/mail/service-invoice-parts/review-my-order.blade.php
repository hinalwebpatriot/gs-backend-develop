<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\DTO\OrderDetailsDTO  $order
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
       cellpadding="0" cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r" style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                        <!-- Workaround: Calculate border radius for Outlook-->
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="0" cellspacing="0"
                                           style="margin: 0 auto;" align="center">
                                        <tr>
                                            <td> <!--[if mso]>
                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
                                                             xmlns:w="urn:schemas-microsoft-com:office:word"
                                                             href="{{ $invoice->url }}"
                                                             style="height:46px;v-text-anchor:middle;mso-wrap-style:none;mso-position-horizontal:center;"
                                                             arcsize="9%" stroke="f" fillcolor="#ac915f">
                                                <w:anchorlock/>
                                                <center style="text-decoration: none; padding: 12px 24px; font-size: 14px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ffffff;">
                                                    REVIEW MY ORDER
                                                </center>
                                                </v:roundrect> <![endif]-->
                                                <!--[if !mso]-->
                                                <a style="display: table-cell; text-decoration: none; padding: 12px 24px; font-size: 14px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#ffffff; border:0px solid ; background-color:#ac915f; border-radius: 3px;"
                                                   href="{{ $invoice->url }}">
                                                    REVIEW MY ORDER
                                                </a>
                                                <!--[endif]-->
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
