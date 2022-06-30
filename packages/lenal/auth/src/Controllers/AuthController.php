<?php

namespace lenal\auth\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use lenal\auth\Facades\CustomAuth;
use lenal\catalog\Facades\CommonHelper;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $data = $request->all();
       if (!empty($errors = CustomAuth::validate('register', $data))) {
           return response()
               ->json(
                   ['message' => $errors],
                   Response::HTTP_BAD_REQUEST
               );
       }
       return CustomAuth::createNewUser($data);
    }

    public function verify()
    {
        $user = User::find(request('id'));
        return $user && CustomAuth::verify($user)
            ? response()->json(null, Response::HTTP_OK)
            : response()->json(null, Response::HTTP_NOT_FOUND);
    }

    public function resendVerify()
    {
        $user = User::find(request('id'));
        return $user && CustomAuth::sendVerifyMail($user)
            ? response()->json(null, Response::HTTP_OK)
            : response()->json(null, Response::HTTP_NOT_FOUND);
    }

    public function resendVerifyEmail()
    {
        try {
            $user = User::query()
                ->where('email', request('email'))
                ->first();

            if (!$user) {
                throw new \Exception(trans('auth.user-not-found'));
            }

            if (!$user->canResendMailVerify()) {
                throw new \Exception(trans('auth.already-resent-or-verified'));
            }

            if (!CustomAuth::sendVerifyMail($user)) {
                throw new \Exception(trans('auth.cant-send-mail-verify'));
            }

            $user->sentResendEmail();
            $user->save();

            return response()->json(['success' => true, 'message' => trans('auth.successfully-resend-mail')], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function login(Request $request)
    {
        if (!empty($errors = CustomAuth::validate('login', $request->all()))) {
            return response()
                ->json(
                    ['message' => $errors],
                    Response::HTTP_BAD_REQUEST
                );
        }

        if (!Auth::attempt(request(['email', 'password']))) {
            return response()->json(
                ['message' => trans('auth.error.wrong_credentials')],
                Response::HTTP_UNAUTHORIZED);
        }

        if (!CustomAuth::isVerified($request->user())) {
            $response = [
                'message' => trans('auth.error.user_not_verified'),
            ];

            if ($request->user()->canResendMailVerify()) {
                $response = array_merge($response, [
                    'resend_label' => trans('auth.resend-link'),
                    'step' => 'verify'
                ]);
            }

            return response()->json($response, Response::HTTP_FORBIDDEN);
        }

        CommonHelper::synchronizeSavedItems($request->user());
        return response()->json(CustomAuth::getToken($request->user()));
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        CommonHelper::clearCookieItems();
        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}
