<?php

namespace lenal\auth\Helpers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Response;

class Auth
{
    public function createNewUser($data)
    {
        $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        if ($user) {
            //$user->assignRole('User');
            if (!$this->sendVerifyMail($user)) {
                return response(
                    [
                        'message' => trans('auth.error.confirm_send'),
                        'user' => $user
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return ['user' => $user];
    }

    public function sendVerifyMail($user)
    {
        if (!$this->isVerified($user)) {
            try{
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e){
                Log::error($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

    public function verify($user)
    {
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return true;
        }
        return false;
    }

    public function validate($rulesSet, $data)
    {
        $rules = [
            'register' => [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string'],
            ],
            'login' => [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string'],
            ]
        ];
        $validator = Validator::make($data, $rules[$rulesSet]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        return [];
    }

    public function getToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return [
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expires' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
    }

    public function isVerified($user)
    {
        return ($user && $user instanceof MustVerifyEmail && $user->hasVerifiedEmail());
    }
}
