<?php

namespace lenal\PriceCalculate;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use lenal\offers\Models\Offer;
use lenal\PriceCalculate\Facades\CountryVat;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Trait PriceRateCalculation
 *
 * @package lenal\PriceCalculate
 */
trait PriceRateCalculation
{
    public static function getCalculatedPriceField(): string
    {
        $gst = 1 + CountryVat::getCountryVat() / 100;
        $base_currency = self::BASE_CURRENCY ?? null;
        $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency(), $base_currency);
        $formula_price = "raw_price * $gst";
        $discount_price = "discount_price * $gst";
        return "CEIL(IF(discount_price IS NOT NULL, $discount_price, $formula_price) / $rate)";
    }

    /**
     * @param  Builder  $query
     * @param  array    $select
     */
    public function scopeWithCalculatedPrice(Builder $query, array $select = [])
    {
        if (count($select) == 0) {
            $select[] = '*';

            $select[] = <<<SQL_STR
                (CASE
                    WHEN `discount_price` IS NOT NULL THEN {$this->getOldPriceFormulaRaw()}
                    ELSE null 
                END) as `old_calculated_price`
SQL_STR;
        }

        $select[] = $this->getPriceFormulaRaw().' as `calculated_price`';

        $query->selectRaw(implode(', ', $select));
    }

    /**
     * @param  Builder  $query
     */
    public function scopeWithCalcPrice(Builder $query)
    {
        $query->addSelect(DB::raw("(CASE WHEN `discount_price` IS NOT NULL THEN {$this->getOldPriceFormulaRaw()} ELSE null END) as `old_calculated_price`"))
            ->addSelect(DB::raw($this->getPriceFormulaRaw().' as `calculated_price`'));
    }

    /**
     * @param  Builder  $query
     */
    public function scopeWithMinCalculatedPrice(Builder $query)
    {
        $query->selectRaw('MIN('.$this->getPriceFormulaRaw().') as `min_calculated_price`');
    }

    /**
     * @param  Builder  $query
     */
    public function scopeWithMaxCalculatedPrice(Builder $query)
    {
        $query->selectRaw('MAX('.$this->getPriceFormulaRaw().') as `max_calculated_price`');
    }

    /**
     * @param $model
     * @return bool
     */
    public function isModel($model)
    {
        return $model instanceof Model;
    }

    public function getSaleOfferRaw($model_type): string
    {
        return '';
    }

    /**
     * @return string
     */
    private function getPriceFormulaRaw()
    {
        $country = CountryVat::getSelectedCountry();
        $base_currency = isset($this->base_currency) ? $this->base_currency : null;
        $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency(), $base_currency);
        $gst = CountryVat::getCountryVat(CountryVat::getSelectedCountry()) / 100;

        // only for Australia
        if ($country == "AU") {
            $formula_price = "inc_price";
            $discount_price = "inc_price / raw_price * discount_price";
        } else {
            $formula_price = "(inc_price / 1.1)";
            $discount_price = "(discount_price + discount_price * $gst)";
        }

        return <<<SQL_STR
            CEIL((
                CASE
                    WHEN discount_price IS NOT NULL THEN $discount_price
                    ELSE $formula_price
                END
            ) / $rate)
SQL_STR;
    }

    /**
     * @return string
     */
    private function getOldPriceFormulaRaw()
    {
        $base_currency = isset($this->base_currency) ? $this->base_currency : null;
        $rate = CurrencyRate::getRate(CurrencyRate::getUserCurrency(), $base_currency);
        $gst = CountryVat::getCountryVat(CountryVat::getSelectedCountry()) / 100;

        $formula_price = "(raw_price  + raw_price * $gst)";
        // only for Australia
        if (CountryVat::getSelectedCountry() == "AU") {
            $formula_price = "inc_price";
        }

        return "CEIL($formula_price / $rate)";
    }

    public function calcDiscount($save = true)
    {
        if (!$this->relationLoaded('offers')) {
            $this->load('offers');
        }

        /** @var Offer $saleOffer */
        $saleOffer = $this->offers
            ->where('enabled', 1)
            ->where('is_sale', 1)
            ->first();

        if (!is_null($this->discount_price) && !$saleOffer) {
            $this->discount_price = null;
            if ($save) {
                $this->save();
            }

            return;
        }

        if ($saleOffer) {
            $this->discount_price = $saleOffer->amountWithDiscount($this->raw_price);
            if ($save) {
                $this->save();
            }
        }
    }
}