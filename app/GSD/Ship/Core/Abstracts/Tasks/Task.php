<?php


namespace GSD\Core\Abstracts\Tasks;

use Exception;

/**
 * Class Task
 * @package GSD\Core\Abstracts\Tasks
 *
 * @method static Task runTask(...$arg)
 */
abstract class Task
{
    /**
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'runTask') {
            $obj = app(static::class);
            return $obj->run(...$arguments);
        }
        throw new Exception(sprintf('Method "%s" not found', $name));
    }
}