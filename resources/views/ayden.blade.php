<form method="post" action="https://test.adyen.com/hpp/pay.shtml" name="adyenForm" target="_blank">
    @foreach($params as $key => $param)
        <input type="text" name="{{ $key }}" value="{{ $param }}" /><br>
    @endforeach
    <input type="submit" value="Send" /><br>
</form>