<div style="font-family: sans-serif">
    <p>{{ trans('api.mail.product_hint.recipient', [ 'recipient_name' => $content['recipient_name']]) }}</p>
    <p>{{ $content['text'] }}</p>
    <p><a href="{{ $full_link }}">{{ $full_link }}</a></p>
    <p>{{ trans('api.mail.product_hint.sender', [ 'sender_name' => $content['sender_name']]) }}</p>
</div>