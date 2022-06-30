<?php


namespace GSD\Containers\Referral\DTO;


use GSD\Ship\Parents\DTO\DTO;

class CustomerDTO extends DTO
{
    public string  $email;
    public ?string $phone;
    public string  $first_name;
    public ?string $last_name;
}