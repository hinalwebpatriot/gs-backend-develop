<?php

namespace lenal\seo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SEO
 * @package lenal\seo\Facades
 * @mixin \lenal\seo\Helpers\SEO
 */
class SEO extends Facade {

    public static function getFacadeAccessor()
    {
        return 'seo';
    }
}
