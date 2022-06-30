<?php

namespace lenal\subscribe\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use lenal\subscribe\Facades\Subscribe;

class SubscribeController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribes',
            'type.*' => ['required', Rule::in(['sale', 'discounts', 'new_collection'])],
            'gender' => ['required', Rule::in(['man', 'woman'])],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(
                    ['errors' => $validator->messages()],
                    Response::HTTP_BAD_REQUEST
                );
        }

        Subscribe::saveSubscriber(request(['email', 'type', 'gender']));

        return response()
            ->json(null, Response::HTTP_OK);
    }

    public function getSubscribeForm()
    {
        return response()->json(Subscribe::getSubscribeForm());
    }
}
