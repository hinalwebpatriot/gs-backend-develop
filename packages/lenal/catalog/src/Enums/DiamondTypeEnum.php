<?php

namespace lenal\catalog\Enums;

use GSD\Ship\Parents\Enums\Enum;

/**
 * @method static DiamondTypeEnum NATURAL()
 * @method static DiamondTypeEnum LAB()
 */
class DiamondTypeEnum extends Enum
{
    private const NATURAL = 'natural';
    private const LAB     = 'lab';

    /**
     * @inheritDoc
     */
    protected function data(): array
    {
        return [
            self::NATURAL => 'Natural',
            self::LAB     => 'Lab-Grown'
        ];
    }
}
