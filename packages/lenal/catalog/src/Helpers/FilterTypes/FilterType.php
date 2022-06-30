<?php

namespace lenal\catalog\Helpers\FilterTypes;


abstract class FilterType
{
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * @return bool applied or not filter
     */
    abstract public function filter();
}