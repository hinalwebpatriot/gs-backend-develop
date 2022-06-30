<?php

namespace lenal\catalog\Helpers\SortTypes;

/**
 * Class SortType
 *
 * @package lenal\catalog\Helpers\SortTypes
 */
abstract class SortType
{
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * @return bool
     */
    abstract public function sort();
}