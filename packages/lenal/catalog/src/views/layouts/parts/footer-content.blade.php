<?php
/**
 * @var $titleImg string
 */
?>
<table style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;" cellpadding="0"
       cellspacing="0">
    <tr>
        <td style="padding-left:15px; padding-right:15px; ">
            <table class="r"
                   style="margin: 0 auto;background-color:#ffffff;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:15px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%;">
                            <tr>
                                <td style="width:100%; text-align: center;">
                                    <img width="540" align="center" style="width: 100%; height: auto;"
                                         src="{{ $imageStorage->url(sprintf('email/%s.gif', $titleImg)) }}"
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
            <table class="r" style="margin: 0 auto;border-spacing: 0;width:600px;"
                   align="center">
                <tr>
                    <td style="padding-top:0px;padding-bottom:30px;padding-left:30px;padding-right:30px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="text-align: center; padding: 0 0 35px;">
                                    <div style="margin: 0 auto; font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:16px;line-height:1.5;max-width:450px;">
                                        tel.: <a href="tel:1300181294"
                                                 style="color: rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); box-shadow: none; text-decoration: none;">
                                            1300 181 294
                                        </a>
                                        <br/>
                                        e-mail: <a href="mailto:info@gsdiamonds.com.au"
                                                   style="color: rgb(0, 0, 0); border-bottom: 1px solid rgb(0, 0, 0); box-shadow: none; text-decoration: none;">
                                            info@gsdiamonds.com.au
                                        </a>
                                        <br/><br/>
                                        Showroom Address:<br/>
                                        Queen Victoria Building,<br/>
                                        455 George Street, Level 2, shop 34-36<br/>
                                        Sydney, Australia.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                           style="table-layout: fixed;">
                                        <tr>
                                            <td width="100%"></td>
                                            <td align="center" style="width: 34px; padding: 0 9px;">
                                                <a style="text-decoration: none;"
                                                   href="https://www.gsdiamonds.com.au/social/facebook">
                                                    <img style="display: block; width: 34px;"
                                                         src="{{ $imageStorage->url('email/fb.gif') }}">
                                                </a>
                                            </td>
                                            <td align="center" style="width: 34px; padding: 0 9px;">
                                                <a style="text-decoration: none;"
                                                   href="https://www.gsdiamonds.com.au/social/instagram">
                                                    <img style="display: block; width: 34px;"
                                                         src="{{ $imageStorage->url('email/inst.gif') }}">
                                                </a>
                                            </td>
                                            <td align="center" style="width: 34px; padding: 0 9px;">
                                                <a style="text-decoration: none;"
                                                   href="https://www.gsdiamonds.com.au/social/pinterest">
                                                    <img style="display: block; width: 34px;"
                                                         src="{{ $imageStorage->url('email/pin.gif') }}">
                                                </a>
                                            </td>
                                            <td align="center" style="width: 34px; padding: 0 9px;">
                                                <a style="text-decoration: none;"
                                                   href="https://www.gsdiamonds.com.au/social/linkedin">
                                                    <img style="display: block; width: 34px;"
                                                         src="{{ $imageStorage->url('email/in.gif') }}">
                                                </a>
                                            </td>
                                            <td width="100%"></td>
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
