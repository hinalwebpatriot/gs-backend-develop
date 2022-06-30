<?php

namespace lenal\banners\Controllers;

use App\Http\Controllers\Controller;
use lenal\banners\Facades\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BannersController extends Controller
{
    public function getPageBanner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()
                ->json(
                    ['errors' => $validator->messages()],
                    Response::HTTP_BAD_REQUEST
                );
        }

        return ($banner = Banners::findPageBanner($request->input('page')))
            ?response()
                ->json(
                     ['image' => asset(Storage::url($banner->image))],
                     Response::HTTP_OK
                )
            : response()
                ->json(
                     null,
                     Response::HTTP_NOT_FOUND
                );
    }
}
