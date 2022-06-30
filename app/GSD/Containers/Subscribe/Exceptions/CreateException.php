<?php


namespace GSD\Containers\Subscribe\Exceptions;


use GSD\Ship\Parents\Exceptions\Exception;

/**
 * Class CreateException
 * @package GSD\Containers\Subscribe\Exceptions
 */
class CreateException extends Exception
{
    protected $message = 'Create error';
}