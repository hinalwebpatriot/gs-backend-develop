<?php


namespace GSD\Ship\Parents\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface HasPriceInterface
 * @package GSD\Ship\Parents\Interfaces
 */
interface HasPriceInterface
{
    /**
     * Возвращает цену продажи
     *
     * @return false|float
     */
    public function getPrice(): float;

    /**
     * Возвращает цену до скидки, если скидки нет то возвращается null
     *
     * @return false|float|null
     */
    public function getOldPrice(): ?float;
}