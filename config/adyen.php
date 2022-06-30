<?php
return [
    'setApplicationName' => env('ADYEN_APP_NAME',''),
    'setUsername' => env('ADYEN_USER_NAME',''),
    'setPassword' => env('ADYEN_PASS',''),
    'setXApiKey' => env('ADYEN_API_KEY',''),
    'merchantAccount' => env('ADYEN_MERCHANT_ACC',''),
    'mode' => env('MODE','TEST'),
    'returnUrl' => 'https://your-company.com/checkout/',
    //for hosted payment page
    'skin' => env('ADYEN_SKIN_CODE'),
    'hmacKey' => env('ADYEN_HMAC_KEY'),
    'webHookHmacKey' => env('ADYEN_WEB_HOOK_HMAC_KEY'),
    'checkoutBaseUrl' => env('ADYEN_CHECKOUT_BASE_URL'),
];
