<?php

namespace lenal\PriceCalculate;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use lenal\PriceCalculate\Facades\CountryVat;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Trait PriceCalculation
 *
 * @package lenal\PriceCalculate
 */
trait PriceCalculation
{
    /**
     * @param Builder $query
     */
    public function scopeWithCalculatedPrice(Builder $query)
    {
        $query->selectRaw('*, ' . $this->getPriceFormulaRaw() . ' as `calculated_price`, ' . $this->getOldPriceFormulaRaw() . ' as `old_calculated_price`');
    }

    /**
     * @param Builder $query
     */
    public function scopeSearchByCalculatedPrice(Builder $query, $from, $to)
    {
        $query->whereRaw($this->getPriceFormulaRaw().' BETWEEN '.$from.' AND '.$to);
    }

    /**
     * @param Builder $query
     */
    public function scopeWithMinCalculatedPrice(Builder $query)
    {
        $query->selectRaw('MIN(' . $this->getPriceFormulaRaw() . ') as `min_calculated_price`');
    }

    /**
     * @param Builder $query
     */
    public function scopeWithMaxCalculatedPrice(Builder $query)
    {
        $query->selectRaw('MAX(' . $this->getPriceFormulaRaw() . ') as `max_calculated_price`');
    }

    /**
     * @param $model
     * @return bool
     */
    public function isModel($model)
    {
        return $model instanceof Model;
    }

    /**
     * @return string
     */
    private function getPriceFormulaRaw()
    {
        $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency(), 'USD');
        $gst = CountryVat::getCountryVat(CountryVat::getSelectedCountry()) / 100;
        $update_percent = config('price_calculate.diamonds_price_update', 1);
        $inc_price = config('price_calculate.diamonds_price_inc', 1);

        $formula_price = "(raw_price / $rate + margin_price / $rate) * $inc_price * $update_percent";
        return "CEIL($formula_price + ($formula_price) * $gst)";
    }

    /**
     * @return string
     */
    private function getOldPriceFormulaRaw()
    {
        if (config('price_calculate.diamonds_price_update', 1) != 1) {
            $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency(), 'USD');
            $gst = CountryVat::getCountryVat(CountryVat::getSelectedCountry()) / 100;
            $inc_price = config('price_calculate.diamonds_price_inc', 1);

            $formula_price = "(raw_price / $rate + margin_price / $rate) * $inc_price";
            return "CEIL($formula_price + ($formula_price) * $gst)";
        }
        return 'null';
    }
}