<?php


namespace GSD\Ship\Parents\Exceptions;


use Throwable;

/**
 * Class Exception
 * @package GSD\Ship\Parents\Exceptions
 */
abstract class Exception extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}