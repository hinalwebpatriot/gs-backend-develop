<?php


namespace GSD\Containers\Referral\DTO;


use Carbon\Carbon;
use GSD\Ship\Parents\DTO\DTO;

/**
 * Class OrderDTO
 * @package GSD\Containers\Referral\DTO
 */
class OrderDTO extends DTO
{
    public int     $id;
    public Carbon  $created_at;
    public ?Carbon $delivered_at;
    public int     $status_id;
    public string  $status;
}