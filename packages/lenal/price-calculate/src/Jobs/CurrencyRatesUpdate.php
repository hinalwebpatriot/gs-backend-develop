<?php

namespace lenal\PriceCalculate\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\PriceCalculate\Models\CurrencyRate as CurrencyRateModel;

class CurrencyRatesUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $base_currency;

    /**
     * Create a new job instance.
     *
     * @param string $base_currency
     */
    public function __construct(string $base_currency)
    {
        $this->base_currency = $base_currency;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exist_rates = CurrencyRateModel::where('to_currency', $this->base_currency)
            ->get();

        $exist_rates->each(function (CurrencyRateModel $rate) {
            $api_rates = CurrencyRate::getApiRates($rate->from_currency);
            if (Arr::get($api_rates, 'result') != 'success') {
                throw new \Exception(json_encode($api_rates));
            }
            if (!key_exists('conversion_rates', $api_rates)) {
                return;
            }

            $api_rates_collection = collect($api_rates['conversion_rates']);

            $new_rate = $api_rates_collection->get($rate->to_currency, null);

            if (is_null($new_rate)) {
                return;
            }

            $rate->rate = $new_rate;
            $rate->save();
        });

    }
}
