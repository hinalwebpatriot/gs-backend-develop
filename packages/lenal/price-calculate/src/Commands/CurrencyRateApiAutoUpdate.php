<?php

namespace lenal\PriceCalculate\Commands;

use lenal\PriceCalculate\Jobs\CurrencyRatesUpdate;
use Illuminate\Console\Command;
use lenal\PriceCalculate\Facades\CurrencyRate;

class CurrencyRateApiAutoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencyRate:sync-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync currency rates from https://www.exchangerate-api.com api resource';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!config('price_calculate.enable_currency_rate_api_sync')) {
            return;
        }

        CurrencyRatesUpdate::dispatch('AUD');
        CurrencyRatesUpdate::dispatch('USD');
        CurrencyRatesUpdate::dispatch('EUR');
        CurrencyRatesUpdate::dispatch('CNY');
        CurrencyRatesUpdate::dispatch('HKD');
        CurrencyRatesUpdate::dispatch('NZD');
    }
}
