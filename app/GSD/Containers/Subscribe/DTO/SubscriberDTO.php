<?php


namespace GSD\Containers\Subscribe\DTO;


use GSD\Ship\Parents\DTO\DTO;

/**
 * Class SubscriberDTO
 * @package GSD\Containers\Subscribe\DTO
 */
class SubscriberDTO extends DTO
{
    public string $email;
    public string $gender;
    public array  $type;
}