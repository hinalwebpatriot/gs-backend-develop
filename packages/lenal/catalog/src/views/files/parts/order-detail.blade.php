<?php
/**
 * @var \lenal\catalog\DTO\OrderDetailsDTO $order
 */
?>

<div style="text-align:center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:20px;line-height:28px;text-transform:uppercase;font-weight:bold;margin-bottom: 15px;">
    Order #{{ $order->id }}
</div>

<div style="text-align:center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:16px;line-height:18px;margin-bottom: 15px;">
    {{ $order->createdAt->format('d M Y g:i A') }}
</div>

<div style="text-align:center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#000000;font-size:14px;line-height:18px;padding: 0 30px;">
    Dear {{ $order->firstName }} {{ $order->lastName }}, thanks for
    your order! Here you may find detailed information about it.
    We sincerely hope to see you again soon!
</div>
