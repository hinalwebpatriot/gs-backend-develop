<?php


namespace GSD\Containers\Referral\Exceptions;


use GSD\Ship\Exceptions\BaseException;

class NotHasConfirmationException extends BaseException
{
    protected $message = 'Promo-code hasn\'t confirmation method';
}