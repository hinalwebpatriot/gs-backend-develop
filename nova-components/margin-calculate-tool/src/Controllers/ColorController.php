<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use lenal\catalog\Models\Diamonds\Color;
use lenal\MarginCalculate\Resources\ColorResource;
use lenal\MarginCalculate\Facades\MarginCalculate;


/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class ColorController extends Controller
{
    /**
     * @return JsonResource
     */
    public function index()
    {
        $manufacturers = MarginCalculate::getAllColors()
            ->map(function (Color $manufacturer) {
                return new ColorResource($manufacturer);
            });

        return response()->json($manufacturers);
    }

    /**
     * @param string $slug
     *
     * @return JsonResource
     */
    public function show(string $slug)
    {
        $color = MarginCalculate::getManufacturer($slug);

        if (is_null($color)) {
            return response()->json([
                'message' => trans('MarginCalculateTool::not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new ColorResource($color));
    }
}
