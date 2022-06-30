<?php


namespace GSD\Containers\Referral\Exceptions;


use GSD\Ship\Exceptions\BaseException;

/**
 * Class TransactionCreateException
 * @package GSD\Containers\Referral\Exceptions
 */
class TransactionCreateException extends BaseException
{
    protected $message = 'Error create referral\'s transaction';
}