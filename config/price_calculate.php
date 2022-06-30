<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Currency rate credentials
    |--------------------------------------------------------------------------
    |
    | This option keeps credentials for currency rate api connection.
    | Api key need to be implemented from service https://www.exchangerate-api.com
    |
    */

    'currency_rate_credentials' => [
        'api_key' => env('CURRENCY_RATE_API_KEY', ''),
    ],


    /*
    |--------------------------------------------------------------------------
    | Auto sync currency rate
    |--------------------------------------------------------------------------
    |
    | This option triggering currency rate synchronize.
    |
    */

    'enable_currency_rate_api_sync' => env('ENABLE_CURRENCY_RATE_API_SYNC', false),


    /*
    |--------------------------------------------------------------------------
    | Default currency rate
    |--------------------------------------------------------------------------
    |
    | This option keeps default currency, from which the exchange rate will be calculate.
    |
    */

    'default_currency' => env('DEFAULT_CURRENCY_RATE', ''),

    /*
    |--------------------------------------------------------------------------
    | Package header keys
    |--------------------------------------------------------------------------
    |
    | This option keeps header keys which will be retrieved by package.
    |
    */

    'header_keys' => [
        'currency' => env('CURRENCY_HEADER_KEY', 'X-DIAMONDS-CURRENCY'),
        'country' => env('COUNTRY_HEADER_KEY', 'X-DIAMONDS-COUNTRY'),
    ],


    /*
     * Update calculated_price on percent rate
     */
    'diamonds_price_update' => env('DIAMONDS_PRICE_UPDATE', 1),
    'diamonds_price_inc' => env('DIAMONDS_PRICE_INC', 1),
];
