<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Resources\ManufacturerResource;
use lenal\MarginCalculate\Facades\MarginCalculate;


/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class ManufacturerController extends Controller
{
    /**
     * @return JsonResource
     */
    public function index()
    {
        $manufacturers = MarginCalculate::getAllManufacturers()
            ->map(function (Manufacturer $manufacturer) {
                return new ManufacturerResource($manufacturer);
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
        $manufacturer = MarginCalculate::getManufacturer($slug)
            ->map(function (Manufacturer $manufacturer) {
                return new ManufacturerResource($manufacturer);
            });

        if (is_null($manufacturer)) {
            return response()->json([
                'message' => trans('MarginCalculateTool::not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($manufacturer);
    }
}
