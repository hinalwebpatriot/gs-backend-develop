<?php


namespace GSD\Ship\Parents\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface HasCenterStoneInterface
 * @package GSD\Ship\Parents\Interfaces
 *
 * @property-read $centerStoneShape
 */
interface HasCenterStoneInterface
{
    public function centerStoneShape(): BelongsTo;

    public function getShapeId(): ?int;
}