<?php

namespace App;

use App\Notifications\VerifyEmailCustom;
use App\Notifications\MyOwnResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Silvanite\Brandenburg\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $resend_email
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, Notifiable, HasApiTokens;


    /**
     * Determines if the User is a Super admin
     * @return null
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Determines if the User is a admin
     * @return null
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isCallCenter()
    {
        return $this->hasRole('call-center');
    }

    public function hasNovaAccess()
    {
        return $this->hasAnyRole(['call-center', 'admin', 'super-admin']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function canResendMailVerify()
    {
        return !$this->email_verified_at && !$this->resend_email;
    }

    public function sentResendEmail()
    {
        $this->resend_email = 1;
    }
}
