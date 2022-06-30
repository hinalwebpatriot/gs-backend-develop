<?php


namespace GSD\Ship\Parents\Enums;


use GSD\Core\Abstracts\Enums\Enum as CoreEnum;

/**
 * Class Enum
 * @package GSD\Ship\Parents\Enums
 *
 * class UserRoleEnum extends AbstractEnum
 * {
 *     private const USER = 'USER';
 *
 *     protected function data(): array
 *     {
 *         return [
 *             self::USER => 'Пользователь'
 *         ];
 *     }
 * }
 *
 */
abstract class Enum extends CoreEnum
{

}