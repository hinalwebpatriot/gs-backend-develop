<?php

namespace lenal\MarginCalculate\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Color;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Models\MarginCalculate as MarginModel;

/**
 * Class MainSlider
 *
 * @package lenal\MainSlider\Helpers
 */
class MarginCalculate
{
    const PAGINATION_LIMIT = 50;

    /**
     * @param $manufacturer_id
     *
     * @return Collection
     */
    public function getMargins($manufacturer_id)
    {
        $margins = MarginModel::with([
            'manufacturer',
            'color',
            'clarity',
        ]);

        is_null($manufacturer_id)
            ? $margins->whereNull('manufacturer_id')
            : $margins->whereHas('manufacturer', function($query) use ($manufacturer_id) {
                $query->where('slug', $manufacturer_id);
            });

        return $margins->get();
    }

    /**
     * @param $params
     *
     * @return MarginModel|null
     */
    public function findMargin(array $params): ?MarginModel
    {
        $test = MarginModel::where('is_round', $params['is_round'])
            ->where(function ($query) use ($params) {
                $query->where('manufacturer_id', $params['manufacturer_id']);
                $query->orWhereNull('manufacturer_id');
            })
            ->where('color_id', $params['color_id'])
            ->where('clarity_id', $params['clarity_id'])
            ->where(function ($query) use ($params) {
                $query->where('carat_min', '<=', $params['carat']);
                $query->where('carat_max', '>=', $params['carat']);
            })
            ->orderBy('manufacturer_id', 'desc')
            ->first();

        return $test;
    }

    /**
     * @param array $margin
     * @return mixed
     * @throws \Exception
     */
    public function setMargin(array $margin)
    {
        $color = Color::where('slug', $margin['color'])->first();
        $clarity = Clarity::where('slug', $margin['clarity'])->first();
        $manufacturer = Manufacturer::where('slug', $margin['manufacturer'])->first();

        try {
            return MarginModel::updateOrCreate(
                [
                    'manufacturer_id' => is_null($manufacturer) ? null : $manufacturer->id,
                    'is_round'        => $margin['is_round'],
                    'clarity_id'      => $clarity->id,
                    'color_id'        => $color->id,
                    'carat_min'       => $margin['carat_min'],
                    'carat_max'       => $margin['carat_max'],
                ],
                [
                    'margin'          => $margin['margin'],
                ]
            );
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllManufacturers()
    {
        return Manufacturer::paginate(self::PAGINATION_LIMIT);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllColors()
    {
        return Color::orderBy('value', 'asc')
            ->paginate(self::PAGINATION_LIMIT);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllClarities()
    {
        return Clarity::orderBy('value', 'asc')
            ->paginate(self::PAGINATION_LIMIT);
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function getManufacturer(string $slug): ?Manufacturer
    {
        return Manufacturer::where('slug', $slug)
            ->first();
    }

    /**
     * @param string $slug
     * @return Color|null
     */
    public function getColor(string $slug): ?Color
    {
        return Color::where('slug', $slug)
            ->first();
    }

    /**
     * @param string $slug
     * @return Clarity|null
     */
    public function getClarity(string $slug): ?Clarity
    {
        return Clarity::where('slug', $slug)
            ->first();
    }
}
