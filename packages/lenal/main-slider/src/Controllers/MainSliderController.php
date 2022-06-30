<?php

namespace lenal\MainSlider\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use lenal\MainSlider\Resources\MainSliderResource;
use lenal\MainSlider\Facades\MainSlider;

/**
 * Class MainSliderController
 *
 * @package lenal\MainSlider\Controllers\Api
 */
class MainSliderController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $main_slider = MainSlider::getMainSliderData();

        return response()
            ->json(is_null($main_slider) ? [] : new MainSliderResource($main_slider));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $is_success = MainSlider::updateMainSliderData($request->get('data'));

        return $is_success
            ? response()->json(["status" => "ok"])
            : response()
                ->json(["status" => "fail"])
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function mobileSlider()
    {
        $slider = MainSlider::getMobileSlider();

        return response()
            ->json(is_null($slider) ? [] : new MainSliderResource($slider));

    }
}
