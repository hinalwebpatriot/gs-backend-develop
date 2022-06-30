<?php

namespace GSD\Ship\Parents\Controllers;

use GSD\Core\Abstracts\Controllers\ApiController as CoreApiController;
use GSD\Ship\Middlewares\ApiJsonHeaderResponse;
use Illuminate\Support\Facades\Response;

/**
 * Class ApiController.
 */
abstract class ApiController extends CoreApiController
{
    protected $middleware = [
        //ApiJsonHeaderResponse::class
    ];
}
