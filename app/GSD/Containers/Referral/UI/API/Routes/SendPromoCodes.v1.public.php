<?php

use GSD\Containers\BuilderPanel\UI\API\Controllers\BuilderPanelController;
use GSD\Containers\Referral\UI\API\Controllers\ReferralController;
use Illuminate\Support\Facades\Route;

/**
 * @apiGroup           Referral
 * @apiName            send
 *
 * @api                {PUT} /v1/referral/send
 * @apiDescription     Сохраняет собранное изделие
 *
 * @apiVersion         1.0.0
 * @apiPermission      None
 *
 * @apiBody            {json}
 * {
 *   "sender": "{sender}",
 *   "sender_first_name": "{sender_first_name}",
 *   "sender_last_name": "{sender_last_name}",
 *   "comment": "{comment}",
 *   "subscribe": {subscribe},
 *   "recipients": [
 *     {
 *       "email": "{{referral_recipient_email}}",
 *       "first_name": "{{referral_recipient_first_name}}",
 *       "last_name": "{{referral_recipient_last_name}}"
 *     }
 *   ]
 * }
 *
 * @apiParam           {string}  sender #емаил держателя рефералки
 * @apiParam           {string}  sender_first_name #имя держателя рефералки
 * @apiParam           {string}  sender_last_name #фамилия держателя рефералки
 * @apiParam           {string}  referral_recipient_email #емаил кому отправляется
 * @apiParam           {string}  referral_recipient_first_name
 * @apiParam           {string}  referral_recipient_last_name
 * @apiParam           {string}  comment #комментарий к письму
 * @apiParam           {boolean} subscribe #подписка на рассылку
 *
 * @apiSuccessExample  {json}  Success-Response:
 * HTTP/1.1 200 OK
 * [
 *   {
 *     "mail@mail.com": "sent" #"sent" - если успех, другое текст ошибки
 *   }
 * ]
 *
 * @apiFailExample  {json}  Fail-Response:
 * HTTP/1.1 404 ERROR
 * {
 *   "message": "Customer not found"
 * }
 *
 * @apiFailExample  {json}  Fail-Response:
 * HTTP/1.1 422 ERROR
 * {
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "recipients.1.email": [
 *       "The recipients.1.email must be a valid email address."
 *     ]
 *   }
 * }
 */
Route::put('send', [ReferralController::class, 'send'])
    ->name('send');
