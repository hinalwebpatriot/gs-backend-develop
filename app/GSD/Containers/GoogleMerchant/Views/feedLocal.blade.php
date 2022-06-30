<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>GS Diamonds</title>
        <link>
        https://www.gsdiamonds.com.au</link>
        <description>
            Quality handcrafted diamond, engagement rings, wedding rings, and other types of jewellery fitted with loose
            diamonds and precious stones at the best price in Sydney CBD Diamond jewellery store
        </description>
        @foreach($products as $product)
            <item>
                <g:id>{{ $product['id'] }}</g:id>
                <g:title>{{ $product['title'] }}</g:title>
                <g:store_code>101</g:store_code>
                <g:description>{{ $product['description'] }}</g:description>
                <g:condition>new</g:condition>
                <g:quantity>5</g:quantity>
                <g:availability>in_stock</g:availability>
                <g:price>{{ $product['price'] }} {{ \lenal\PriceCalculate\Facades\CurrencyRate::getUserCurrency() }}</g:price>
                @if ($product['sale_price'])
                    <g:sale_price>{{ $product['sale_price'] }} {{ \lenal\PriceCalculate\Facades\CurrencyRate::getUserCurrency() }}</g:sale_price>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
