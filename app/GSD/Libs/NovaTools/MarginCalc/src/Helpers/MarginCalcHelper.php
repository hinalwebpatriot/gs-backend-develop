<?php


namespace GSD\Libs\NovaTools\MarginCalc\Helpers;


use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Models\MarginCalculate;

/**
 * @TODO    после переноса поменять модели
 *
 * Class MarginCalcHelper
 * @package GSD\Libs\NovaTools\MarginCalc\Helpers
 */
class MarginCalcHelper
{
    /**
     * Возвращает список групп каратности
     *
     * @return array
     */
    public static function getCaratRange(): array
    {
        return MarginCalculate::query()
            ->select(['carat_min', 'carat_max'])
            ->where('is_round', true)
            ->groupBy(['carat_min', 'carat_max'])
            ->get()
            ->toArray();
    }

    /**
     * Возвращает список цветов
     *
     * @return array
     */
    public static function getColors(): array
    {
        return Color::query()->select(['id', 'title'])->get()->toArray();
    }

    /**
     * Возвращает список чистоты
     *
     * @return array
     */
    public static function getClarities(): array
    {
        return Clarity::query()->select(['id', 'title'])->get()->toArray();
    }

    /**
     * Возвращает список поставщиков
     *
     * @return array
     */
    public static function getManufacturers(): array
    {
        return Manufacturer::query()->select(['id', 'title'])->get()->toArray();
    }

    /**
     * Возвращает маржинальный процент
     *
     * @param  float  $min
     * @param  float  $max
     * @param  int    $color
     * @param  int    $clarity
     *
     * @return float
     */
    public static function getMargin(
        int $manufacturer,
        float $min,
        float $max,
        int $color,
        int $clarity,
        bool $isRound
    ): float {
        $margin = MarginCalculate::query()
            ->where('is_round', $isRound)
            ->where(function ($query) use ($manufacturer) {
                $query->whereNull('manufacturer_id');
                if ($manufacturer) {
                    $query->orWhere('manufacturer_id', $manufacturer);
                }
            })
            ->where('color_id', $color)
            ->where('clarity_id', $clarity)
            ->where('carat_min', $min)
            ->where('carat_max', $max)
            ->orderBy('manufacturer_id', 'desc')
            ->first();

        if (!$margin) {
            return 0;
        }
        return $margin->margin;
    }
}