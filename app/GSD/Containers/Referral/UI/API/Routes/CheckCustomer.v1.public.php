<?php

use GSD\Containers\Referral\UI\API\Controllers\ReferralController;
use Illuminate\Support\Facades\Route;

/**
 * @apiGroup           Referral
 * @apiName            check
 *
 * @api                {POST} /v1/referral/check
 * @apiDescription     Проверяет на существование емейла в базе
 *
 * @apiVersion         1.0.0
 * @apiPermission      None
 *
 * @apiBody            {json}
 * {
 *   'email': {email}
 * }
 *
 * @apiParam           {string} email
 *
 * @apiSuccessExample  {json}  Success-Response:
 * HTTP/1.1 200 OK
 * {
 *   "is_valid": true
 * }
 */
Route::post('check', [ReferralController::class, 'check'])
    ->name('check');
