<?php

namespace lenal\MarginCalculate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Facades
 * @method static getMargins($manufacturer_id): Collection
 * @method static findMargin(array $params): MarginCalculate|null
 * @method static setMargin(array $margin): MarginCalculate|\Exception
 * @method static getAllManufacturers(): LengthAwarePaginator
 * @method static getAllColors(): LengthAwarePaginator
 * @method static getAllClarities(): LengthAwarePaginator
 * @method static getManufacturer(string $slug): Manufacturer|null
 * @method static getColor(string $slug): Color|null
 * @method static getClarity(string $slug): Clarity|null
 */
class MarginCalculate extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'margin_calculate';
    }
}
