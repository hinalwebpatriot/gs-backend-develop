<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\MarginCalculate\Facades\MarginCalculate;
use lenal\MarginCalculate\Models\MarginCalculate as MarginCalculateModel;
use Illuminate\Support\Facades\DB;

/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class MarginSyncController extends Controller
{
    /**
     * @param Request $request
     * @param string  $manufacturer_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, string $manufacturer_id)
    {
        $manufacturer = Manufacturer::where('slug', $manufacturer_id)->first();

        if (is_null($manufacturer)) {
            return response()->json([
                'message' => trans('MarginCalculateTool::api.manufacturer_not_exist'),
            ], Response::HTTP_NOT_FOUND);
        }

        $margins = MarginCalculate::getMargins(null);

        try {
            DB::beginTransaction();

            $old_margins = MarginCalculate::getMargins($manufacturer_id);
            MarginCalculateModel::destroy($old_margins->pluck('id'));

            $margins->each(function (MarginCalculateModel $margin) use ($manufacturer) {
                $copy_margin = $margin->replicate();

                $copy_margin->manufacturer_id = $manufacturer->id;

                $copy_margin->save();
            });

            DB::commit();

            return response()->json(trans('MarginCalculateTool::api.margins_sync_success'));
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
