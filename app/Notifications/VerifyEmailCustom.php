<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailCustom extends VerifyEmail
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $apiUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(7200),
            ['id' => $notifiable->getKey()]
        );
        $frontConfirmPath = env('FRONTEND_URL').'/auth/confirm';
        $frontUrl = str_replace(route('verification.verify'), $frontConfirmPath, $apiUrl);
        return $frontUrl;
    }
}
