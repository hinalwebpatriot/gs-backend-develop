<?php

namespace GSD\Containers\Import\Enums;

use GSD\Ship\Parents\Enums\Enum;


class ImportLogTypeEnum extends Enum
{
    private const TYPE_ERROR = 'error';
    /**
     * @inheritDoc
     */
    protected function data(): array
    {
        return [
            self::TYPE_ERROR => 'Error'
        ];
    }
}