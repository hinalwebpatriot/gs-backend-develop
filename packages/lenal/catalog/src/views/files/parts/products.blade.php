<?php
/**
 * @var \Illuminate\Support\Facades\Storage $imageStorage
 * @var \lenal\catalog\DTO\OrderDetailsDTO  $order
 */
?>

<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:32px; font-weight:bold;line-height: 32px;text-align:center; margin-bottom: 30px;">
    ORDER & PAYMENT DETAILS:
</div>
<div style="padding: 0 30px;">
    @foreach($order->items as $item)
        @include('catalog::files.parts.product', ['item' => $item])
        @if (!$loop->last)
            <div style="margin-bottom: 30px;">&nbsp;</div>
        @endif
    @endforeach
</div>
