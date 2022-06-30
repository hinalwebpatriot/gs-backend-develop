<?php

namespace lenal\PriceCalculate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class PriceCalculate
 *
 * @package lenal\MainSlider\Facades
 * @method static getExistVats(): Illuminate\Database\Eloquent\Collection
 * @method static getCountryVat(): float|null
 * @method static getSelectedCountry(): string|null
 */
class CountryVat extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'country-vat';
    }
}
