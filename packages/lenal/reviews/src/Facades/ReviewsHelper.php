<?php

namespace lenal\reviews\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ReviewsHelper
 * @mixin \lenal\reviews\Helpers\ReviewsHelper
 */
class ReviewsHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'reviews';
    }
}
