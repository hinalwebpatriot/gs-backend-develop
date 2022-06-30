<div style="font-family: sans-serif">

    @section('logo')
        <img src= "{{ asset('/svg/gs_diamonds_logo.png') }}">
    @show

    @section('main_text')
        {!! trans('api.mail.order_base.top_text') !!}
    @show

    @hasSection('order_details')
        @yield('order_details')
    @else
        @include('catalog::layouts.order_details')
    @endif

    @section('bottom_text')
        {!! trans('api.mail.order_base.bottom_text') !!}
    @show

</div>