<?php
/**
 * @var $item \lenal\catalog\DTO\OrderDetailsItemDTO
 */
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%"
       style="table-layout: fixed;">
    <tr>
        <td class="t-emailBlock" valign="top" style="width: 200px;">
            <a href="{{ $item->url }}">
                <img width="200" style="display: block; width: 100% !important;"
                     src="{{ $item->previewImage }}">
            </a>
        </td>
        <td class="t-emailBlock t-emailBlockPadding" valign="top"
            style="padding-left: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                <tr>
                    <td style="text-align: left; padding-bottom: 5px;">
                        <a href="{{ $item->url }}"
                           style="text-decoration: none; color: #222222;">
                            <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;line-height:1.2;">
                                {{ $item->name }}
                            </div>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#777777;font-size:14px;line-height:1.4;">
                            @isset($item->productMetal)
                                <p style="text-align: left;">Metal: {{ $item->productMetal }}</p>
                            @endisset

                            @isset($item->productBandWidth)
                                <p style="text-align: left;">Band Width: {{ $item->productBandWidth }} mm</p>
                            @endisset

                            @isset($item->productCarat)
                                <p style="text-align: left;">Carat: {{ $item->productCarat }} </p>
                            @endisset

                            @isset($item->productColor)
                                <p style="text-align: left;">Color: {{ $item->productColor}} </p>
                            @endisset

                            @isset($item->productShape)
                                <p style="text-align: left;">Shape: {{ $item->productShape }} </p>
                            @endisset

                            @isset($item->ringSize)
                                <p style="text-align: left;">Ring size: {{ $item->ringSize }}</p>
                            @endisset

                            @isset($item->ringEngraving)
                                <p style="text-align: left;">Engraving: {{ $item->ringEngraving }}</p>
                                <p style="text-align: left;">Engraving Font: {{ $item->ringEngraving }}</p>
                            @endisset

                            <p style="text-align: left;">
                                {{ trans('api.mail.order_details.sku', ['sku' => $item->sku]) }}
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 12px;">
                        <table border="0" cellpadding="0" cellspacing="0"
                               align="left" style="margin-left: 0;">
                            <tr>
                                <td>
                                <!--[if mso]> <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $item->previewImage }}" style="height:32px;v-text-anchor:middle;width:100%;" strokecolor="#000000" > <w:anchorlock/> <center style="text-decoration: none; padding: 6px 12px; font-size: 12px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#000000;"> View on website &gt; </center> </v:roundrect> <![endif]-->
                                    <!--[if !mso]-->
                                    <a style="display: table-cell; text-decoration: none; padding: 6px 12px; font-size: 12px; text-align: center; font-weight: bold; font-family:Helvetica Neue, Helvetica, Arial, sans-serif; width: 100%;color:#000000; "
                                       href="{{ $item->url }}">
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

