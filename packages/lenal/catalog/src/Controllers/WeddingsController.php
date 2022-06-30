<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Facades\WeddingsHelper;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Requests\ImportWeddingRequest;
use lenal\catalog\Requests\StoreWeddingPostRequest;
use lenal\catalog\Requests\UpdateWeddingPutRequest;
use lenal\catalog\Resources\WeddingRingGroupCollection;
use lenal\catalog\Resources\WeddingRingGroupResource;
use lenal\catalog\Resources\WeddingRingResource;
use \Illuminate\Http\JsonResponse;

/**
 * Class WeddingsController
 *
 * @package lenal\catalog\Controllers
 */
class WeddingsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return WeddingRingGroupCollection
     */
    public function index(Request $request): WeddingRingGroupCollection
    {
        return WeddingsHelper::getWeddingRings($request);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $wedding = WeddingsHelper::getWeddingRing($id);

        if (is_null($wedding)) {
            return response()->json([
                'message' => trans('api.error.not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        CommonHelper::addToViewed($id, 'wedding-rings');

        return response()
            ->json(new WeddingRingGroupResource($wedding));
    }

    /**
     * @param StoreWeddingPostRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreWeddingPostRequest $request): JsonResponse
    {
        $wedding = WeddingsHelper::createWedding($request);

        return response()->json(new WeddingRingResource($wedding));
    }

    /**
     * @param UpdateWeddingPutRequest $request
     * @param string                  $sku
     *
     * @return JsonResponse
     */
    public function update(UpdateWeddingPutRequest $request, string $sku): JsonResponse
    {
        try {
            $wedding = WeddingsHelper::updateWedding($sku, $request);

            return response()
                ->json(new WeddingRingResource($wedding));
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function import(ImportWeddingRequest $request)
    {
        $sku = $request->get('sku');

        try {
            /** @var WeddingRing $wedding */
            if (WeddingRing::query()->where('sku', $request->get('sku'))->exists()) {
                $wedding = WeddingsHelper::updateWedding($sku, new Request($request->only('raw_price', 'inc_price', 'metal')));
            } else {
                $wedding = WeddingsHelper::createWedding($request);
            }

            $imageInfo = $request->get('image_info');
            if ($imageInfo) {
                WeddingsHelper::importImages($wedding, Arr::get($imageInfo, 'folder'), Arr::get($imageInfo, 'filename'));
            }
        } catch (\Exception $e) {
            logger()->channel('fail-wedding')->error($e);
        }
    }

    public function moreMetalsSlider($id)
    {
        return WeddingsHelper::moreMetalsSlider($id);
    }

    /**
     * @return JsonResponse
     */
    public function getFilters(): JsonResponse
    {
        return response()->json(WeddingsHelper::getFilters());
    }

    public function similarCollectionsSlider($id)
    {
        return WeddingsHelper::similarCollectionsSlider($id);
    }

}