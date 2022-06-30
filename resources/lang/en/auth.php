<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'error' => [
        'confirm_send' => "Can't send confirmation email because of mail service problems",
        'wrong_credentials' => 'Incorrect password or email',
        'user_not_verified' => 'Your email is not verified!',
        'not_authorized' => 'User is not authorized'
    ],
    'user-not-found' => 'User not found',
    'already-resent-or-verified' => 'The E-mail was already resent',
    'cant-send-mail-verify' => 'The E-mail was already resent or user is verified',
    'successfully-resend-mail' => 'The mail successfully sent!',
    'resend-link' => 'Resend email to verify',
];
