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
                <g:description>{{ $product['description'] }}</g:description>
                <g:link>{{ $product['linkProduct'] }}</g:link>
                <g:image_link>{{ $product['linkImage'] }}</g:image_link>
                <g:condition>new</g:condition>
                <g:availability>in_stock</g:availability>
                <g:price>{{ $product['price'] }} {{ \lenal\PriceCalculate\Facades\CurrencyRate::getUserCurrency() }}</g:price>
                @if ($product['sale_price'])
                    <g:sale_price>{{ $product['sale_price'] }} {{ \lenal\PriceCalculate\Facades\CurrencyRate::getUserCurrency() }}</g:sale_price>
                @endif
                <g:mpn>{{ $product['id'] }}</g:mpn>
                <g:brand>GS Diamonds</g:brand>
                <g:google_product_category>{{ $product['category'] }}</g:google_product_category>
                <g:custom_label_0>{{ $feedType }}</g:custom_label_0>
                <g:custom_label_1>{{ ($product['has_new_renders'] ? 'NewRender' : 'OldRender') }}</g:custom_label_1>
                @if (!$product['has_new_renders'])
                    <g:sell_on_google_quantity>3</g:sell_on_google_quantity>
                @endif
                <g:custom_label_2>{{ !!$product['is_best_for_merchant'] ? 'Yes' : 'No' }}</g:custom_label_2>
            </item>
        @endforeach
    </channel>
</rss>
