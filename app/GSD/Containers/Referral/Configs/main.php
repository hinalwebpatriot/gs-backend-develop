<?php

return [
    /**
     * Количество дней для проверки незакрытых транзакций который привязаны к онлайн заказам
     */
    'checkOldTransactionsDays' => 35,
    'giftCardApiKey' => env('GIFTPAY_APIKEY'),
    'giftCardSandbox' => env('GIFTPAY_SANDBOX', false),
    'giftCardEmailFrom' => 'GS Diamonds',
    'giftCardEmailText' => 'Please accept our sincere gratitude for you referring us to one of your friends!<br><br>GS Diamonds thanks you for being such a loyal customer, and as a token of our gratitude, we are sending you this gift card with the benefits you have earned.'
];