<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DiamondsHelper
 *
 * @package lenal\catalog\Facades
 * @method static applyFilter($filter_type): void
 * @method static applyOrder($sort_type): void
 * @method static hasAppliedFilter(): bool
 */
class FilterBuilderHelper extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'filter_builder_helper';
    }
}
