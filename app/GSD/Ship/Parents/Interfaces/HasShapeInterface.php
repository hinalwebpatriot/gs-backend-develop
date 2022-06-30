<?php


namespace GSD\Ship\Parents\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface HasShapeInterface
 * @package GSD\Ship\Parents\Interfaces
 *
 * @property-read $shape
 */
interface HasShapeInterface
{
    public function shape(): BelongsTo;

    public function getShapeId(): ?int;
}