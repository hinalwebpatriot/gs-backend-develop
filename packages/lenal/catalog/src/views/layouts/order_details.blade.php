<?php
/**
 * @var lenal\catalog\Models\Order $order
 */
?>
<div style="font-family: sans-serif">
    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.order_details') }}</p>
    <div style="text-align: left">
        <p>{{ $order->created_at->format('d M Y g:i:s A') }}</p>
        <p>{{ trans('api.mail.order_details.your_order', ['id' => $order->id]) }}</p>
    </div>

    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.products') }}</p>
    <table>
        <thead>
            <tr>
                <th>{{ trans('api.mail.order_details.items') }}</th>
                <th>{{ trans('api.mail.order_details.quantity') }}</th>
                <th>{{ trans('api.mail.order_details.price') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($order->cartItems as $item)
            <tr>
                <td>
                    {{ $item->product->title }}<br>
                    {{ trans('api.mail.order_details.sku', ['sku' => $item->product->slug]) }}<br>
                    @if ($item->engraving)
                    Engraving: {{ $item->engraving }}<br>
                    Engraving Font: {{ $item->engraving_font }}<br>
                    @endif
                </td>
                <td>1</td>
                <td>
                    {{ trans("api.currency_format.$order->currency", ['sum' => $item->price]) }}

                    @if ($item->price_old)
                    <div>
                        <span style="text-decoration: line-through;">{{ trans("api.currency_format.$order->currency", ['sum' => $item->price_old]) }}</span>
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach

        @if ($order->promocode)
        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.promocode') }}</th>
            <td>{{ $order->promocode->code }}</td>
        </tr>
        @endif

        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.subtotal') }}</th>
            <td>{{ trans("api.currency_format.$order->currency", ['sum' => $order->subtotal]) }}</td>
        </tr>
        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.shipping_total') }}</th>
            <td>{{ trans("api.currency_format.$order->currency", ['sum' => $order->shipping]) }}</td>
        </tr>
        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.grand1') }}</th>
            <td>{{ trans("api.currency_format.$order->currency", ['sum' => $order->excluding_tax]) }}</td>
        </tr>
        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.tax') }}</th>
            <td>{{ trans("api.currency_format.$order->currency", ['sum' => $order->tax]) }}</td>
        </tr>
        <tr>
            <th colspan="2">{{ trans('api.mail.order_details.grand2') }}</th>
            <td>{{ trans("api.currency_format.$order->currency", ['sum' => $order->including_tax]) }}</td>
        </tr>
        </tbody>
    </table>

    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.shipping') }}</p>
    <div style="text-align: left">
        @if ($order->showroom)
            <p>{{ trans('api.mail.order_details.shipping_showroom') }}:</p>
            <p>{{ $order->first_name }} {{ $order->last_name }},</p>
            <p>{{ $order->showroom->geo_title }},</p>
            <p>{{ $order->showroom->address }},</p>
            <p>{{ $order->showroom->country->title }}</p>
        @else
            <p>{{ $order->first_name }} {{ $order->last_name }},</p>
            <p>{{ $order->address }},</p>
            <p>{{ $order->town_city }}, {{ $order->state }}, {{ $order->zip_postal_code }}</p>
            <p>{{ $order->country }}</p>
            <p>T: {{ $order->phone_number }}</p>
        @endif
    </div>

    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.shipping_instructions') }}</p>
    <p style="text-align: left">{{ trans('api.mail.order_details.shipping_free') }}</p>

    @if (!$order->billing_address)
    <p style="text-align: center; font-weight: bold">{{ trans('api.mail.order_details.billing') }}</p>
    <div style="text-align: left">
        <p>{{ $order->first_name_home }} {{ $order->last_name_home }},</p>
        <p>{{ $order->address_home }},</p>
        <p>{{ $order->town_city_home }}, {{ $order->state_home }}, {{ $order->zip_postal_code_home }}</p>
        <p>{{ $order->country_home }}</p>
        <p>T: {{ $order->phone_number_home }}</p>
    </div>
    @endif

    @yield('payment_info')

</div>