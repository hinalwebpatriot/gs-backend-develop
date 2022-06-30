<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use lenal\MarginCalculate\Resources\MarginResource;
use lenal\MarginCalculate\Facades\MarginCalculate;


/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class MarginController extends Controller
{
    /**
     * @param $manufacturer_id
     *
     * @return JsonResource
     */
    public function index($manufacturer_id = null)
    {
        $margins = MarginCalculate::getMargins($manufacturer_id ?: null);

        return response()->json(MarginResource::collection($margins));
    }

    public function params(Request $request)
    {
        return response()->json([
            'hasUpdatePermission' => $request->user()->isSuperAdmin() || $request->user()->isAdmin()
        ]);
    }

    /**
     * @param Request $request
     * @param string $manufacturer_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $manufacturer_id = null)
    {
        $params = $request->all();

        try {
            if (!$request->user()->isSuperAdmin() && !$request->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, you have no permission!',
                ]);
            }

            MarginCalculate::setMargin($params);

            return response()->json(array_merge($params, [
                'success' => true,
            ]));
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
