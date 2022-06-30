<?php


namespace GSD\Containers\Referral\DTO;


use GSD\Ship\Parents\DTO\DTO;

/**
 * Class RecipientDTO
 * @package GSD\Containers\Referral\DTO
 */
class RecipientDTO extends DTO
{
    public string $email;
    public string $first_name;
    public ?string $last_name;
}