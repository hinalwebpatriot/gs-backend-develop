<?php

use Illuminate\Database\Seeder;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\PriceCalculate\Models\CurrencyRate as CurrencyRateModel;

/**
 * Class CurrencyRateSeeder
 */
class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_currency[] = CurrencyRate::getBaseCurrency();
        $all_currency[] = "AUD";
        $available_currency_list = [
            'USD',
            'AUD',
            'NZD',
            'CNY',
            'HKD',
            'EUR',
        ];
        foreach ($all_currency as $base_currency) {
            foreach ($available_currency_list as $currency) {
                $rates = CurrencyRate::getApiRates($currency);

                if (!key_exists('rates', $rates)) {
                    continue;
                }

                $api_rates = collect($rates['rates']);
                $rate = $api_rates->get($base_currency, null);

                if (is_null($rate)) {
                    continue;
                }

                CurrencyRateModel::create([
                    'from_currency' => $currency,
                    'to_currency'   => $base_currency,
                    'rate'          => floatval($rate),
                ]);
            }
        }
    }
}
