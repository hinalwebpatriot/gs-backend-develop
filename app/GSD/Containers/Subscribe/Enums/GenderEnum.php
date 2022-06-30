<?php


namespace GSD\Containers\Subscribe\Enums;


use GSD\Ship\Parents\Enums\Enum;

/**
 * Гендерная принадлежность
 *
 * Class GenderEnum
 * @package GSD\Containers\Subscribe\Enums
 *
 * @method static GenderEnum MAN()
 * @method static GenderEnum WOMAN()
 * @method static GenderEnum REFERRER()
 */
class GenderEnum extends Enum
{
    private const MAN      = 'man';
    private const WOMAN    = 'woman';
    private const REFERRER = 'referrer';

    /**
     * @inheritDoc
     */
    protected function data(): array
    {
        return [
            self::MAN      => 'Man',
            self::WOMAN    => 'Woman',
            self::REFERRER => 'Referrer',
        ];
    }
}