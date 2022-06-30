<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use lenal\catalog\Models\Diamonds\Clarity;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Resources\ClarityResource;
use lenal\MarginCalculate\Facades\MarginCalculate;


/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class ClarityController extends Controller
{
    /**
     * @return JsonResource
     */
    public function index()
    {
        $manufacturers = MarginCalculate::getAllClarities()
            ->map(function (Clarity $manufacturer) {
                return new ClarityResource($manufacturer);
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
        $clarity = MarginCalculate::getManufacturer($slug);

        if (is_null($clarity)) {
            return response()->json([
                'message' => trans('MarginCalculateTool::not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new ClarityResource($clarity));
    }
}
