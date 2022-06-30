<?php

namespace lenal\PriceCalculate\Helpers;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use lenal\PriceCalculate\Models\CurrencyRate as CurrencyRateModel;

/**
 * Class CurrencyRate
 *
 * @package lenal\MainSlider\Helpers
 */
class CurrencyRate
{
    const BASE_API_URL  = 'https://v6.exchangerate-api.com/v6/%s/latest/';
    const BASE_CURRENCY = 'USD';

    /**
     * @param string $base_currency
     *
     * @return array
     */
    public function getApiRates(string $base_currency = ''): array
    {
        $uri = sprintf(
            self::BASE_API_URL,
            config('price_calculate.currency_rate_credentials.api_key')
        );
        $client = new Client([
            'base_uri' => $uri,
        ]);
        $currency = $base_currency ?: $this->getBaseCurrency();
        $response = $client->get($currency)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * @return Collection
     */
    public function getExistRates(): Collection
    {
        return CurrencyRateModel::where('to_currency', $this->getBaseCurrency())
            ->get()
            ->pluck('rate', 'from_currency');
    }

    /**
     * @param string      $currency
     *
     * @param null|string $base_currency
     *
     * @return float
     */
    public function getRate(string $currency, ?string $base_currency = null): float
    {
        $base_currency = $base_currency ?? $this->getBaseCurrency();
        $cache_key = $base_currency . '.' . $currency;
        $cache_minutes = 5;

        $rate = Cache::remember($cache_key, $cache_minutes, function () use ($currency, $base_currency) {
            return CurrencyRateModel::where('to_currency', $base_currency)
                ->where('from_currency', $currency)
                ->first();
        });

        return is_null($rate) ? 1.0 : $rate->rate;
    }

    /**
     * @return string
     */
    public function getUserCurrency(): string
    {
        $currency_header_key = config('price_calculate.header_keys.currency');
        $location = json_decode(Cookie::get("locations_selected"), true);
        if ($location) {
            return $location["currency"]["code"];
        }
        return !is_null($currency_header_key) && request()->hasHeader($currency_header_key)
            ? request()->header($currency_header_key)
            : $this->getBaseCurrency();
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        $default_config_currency = config('price_calculate.default_currency');

        return $default_config_currency ?: self::BASE_CURRENCY;
    }

    public function convert($amount, $currentCurrency, $targetCurrency)
    {
        if ($currentCurrency == $targetCurrency) {
            return $amount;
        }

        $rate = $this->getRate($currentCurrency, $targetCurrency);

        return (float) $amount * $rate;
    }

    public function convertByUserCurrency($amount, $currentCurrency = 'AUD')
    {
        return ceil($this->convert($amount, $currentCurrency, CurrencyRate::getUserCurrency()));
    }
}
