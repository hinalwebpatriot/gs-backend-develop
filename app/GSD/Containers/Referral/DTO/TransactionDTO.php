<?php


namespace GSD\Containers\Referral\DTO;


use Carbon\Carbon;
use GSD\Ship\Parents\DTO\DTO;

/**
 * Class TransactionDTO
 * @package GSD\Containers\Referral\DTO
 */
class TransactionDTO extends DTO
{
    public int     $owner_id;
    public int     $code_id;
    public ?int    $order_id;
    public ?int    $tower_id;
    public float   $order_sum;
    public float   $payment;
    public ?Carbon $delivered_at;
    public ?Carbon $approved_at;
}