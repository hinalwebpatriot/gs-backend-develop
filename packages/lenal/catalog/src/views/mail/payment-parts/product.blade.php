<?php
/**
 * @var $item \lenal\catalog\DTO\OrderDetailsItemDTO
 */
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%"
       style="table-layout: fixed;">
    <tr>
        <td class="t-emailBlock t-emailBlockPadding" valign="top"
            style="padding-left: 20px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%;">
                <tr>
                    <td style="text-align: left; padding-bottom: 5px;">
                        <a href="{{ $item->url }}"
                           style="text-decoration: none; color: #222222;">
                            <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#222222;font-size:20px;line-height:1.2;">
                                {{ $item->name }} -
                                {{ trans("api.currency_format.$item->costCurrency", [
                                    'sum' => number_format($item->cost)
                                ]) }}
                            </div>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        <div style="font-weight: normal; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#777777;font-size:14px;line-height:1.4;">
                            @isset($item->productMetal)
                                Metal: {{ $item->productMetal }},
                            @endisset

                            @isset($item->productBandWidth)
                                Band Width: {{ $item->productBandWidth }} mm,
                            @endisset

                            @isset($item->productCarat)
                                Carat: {{ $item->productCarat }},
                            @endisset

                            @isset($item->productColor)
                                Color: {{ $item->productColor}},
                            @endisset

                            @isset($item->productShape)
                                Shape: {{ $item->productShape }},
                            @endisset

                            @isset($item->ringSize)
                                Ring size: {{ $item->ringSize }},
                            @endisset

                            @isset($item->ringEngraving)
                                Engraving: {{ $item->ringEngraving }},
                                Engraving Font: {{ $item->ringEngraving }},
                            @endisset
                            {{ trans('api.mail.order_details.sku', ['sku' => $item->sku]) }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

