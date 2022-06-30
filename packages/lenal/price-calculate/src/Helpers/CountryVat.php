<?php

namespace lenal\PriceCalculate\Helpers;


use Illuminate\Support\Facades\Cache;
use lenal\PriceCalculate\Models\CountryVat as CountryVatModel;
use lenal\AppSettings\Models\Location;
use Illuminate\Support\Facades\Cookie;

/**
 * Class CountryVat
 *
 * @package lenal\MainSlider\Helpers
 */
class CountryVat
{
    const DEFAULT_VAT = 0;
    const DEFAULT_CODE_COUNTRY = "AU";

    /**
     * @param string|null  $code
     *
     * @return float
     */
    public function getCountryVat(string $code = null): float
    {
        $key = $this->getSelectedCountry();
        $ttl = 60;
        $code = $code ?? $this->getSelectedCountry();

        $vat = Cache::remember($key, $ttl, function () use ($code) {
            return Location::where('code', strtoupper($code))
                ->first();
        });

        return $vat->vat ?? self::DEFAULT_VAT;
    }

    /**
     * @return string|null
     */
    public function getSelectedCountry(): ?string
    {
        $location = json_decode(Cookie::get("locations_selected"), true);
        if ($location) {
            return $location["location"]["code"];
        }
        $country_header_key = config('price_calculate.header_keys.country', '');
        if (request()->header($country_header_key)) {
            return request()->header($country_header_key);
        }
        return self::DEFAULT_CODE_COUNTRY;
    }
}
