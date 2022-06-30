<?php

namespace lenal\PriceCalculate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CurrencyRate
 *
 * @package lenal\MainSlider\Facades
 * @method static getApiRates(string $base_currency = ''): array
 * @method static getExistRates(): Illuminate\Database\Eloquent\Collection
 * @method static getRate(string $currency, string $baseCurrency): float|null
 * @method static getBaseCurrency(): string
 * @method static getUserCurrency(): string
 * @method static convert($amount, $currentCurrency, $targetCurrency)
 * @method static convertByUserCurrency($amount, $currentCurrency = 'AUD')
 */
class CurrencyRate extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'currency-rate';
    }
}
