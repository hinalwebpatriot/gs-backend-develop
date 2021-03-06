<?php

namespace GSD\Core\Abstracts\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelBaseController;

/**
 * Class Controller.
 *
 * A.K.A (app/Http/Controllers/Controller.php)
 *
 * Should not extend from this class, instead use the ApiController.
 */
abstract class Controller extends LaravelBaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
