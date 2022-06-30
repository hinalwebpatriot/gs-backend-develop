<?php


namespace GSD\Containers\Subscribe\Enums;


use GSD\Ship\Parents\Enums\Enum;

/**
 * Типы подписок
 *
 * Class TypeEnum
 * @package GSD\Containers\Subscribe\Enums
 *
 * @method static TypeEnum SALE()
 * @method static TypeEnum DISCOUNTS()
 * @method static TypeEnum NEW_COLLECTION()
 */
class TypeEnum extends Enum
{
    private const SALE           = 'sale';
    private const DISCOUNTS      = 'discounts';
    private const NEW_COLLECTION = 'new_collection';

    /**
     * @inheritDoc
     */
    protected function data(): array
    {
        return [
            self::SALE           => 'Sale',
            self::DISCOUNTS      => 'Discounts',
            self::NEW_COLLECTION => 'New Collection',
        ];
    }
}