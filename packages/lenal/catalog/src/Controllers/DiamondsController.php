<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use lenal\catalog\Facades\ConstructorHelper;
use lenal\catalog\Facades\DiamondsHelper;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Manufacturer;
use lenal\catalog\Models\ImportDiamondStatistic;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Requests\StoreDiamondPostRequest;
use lenal\catalog\Requests\UpdateDiamondPostRequest;
use lenal\catalog\Resources\DiamondResource;

class DiamondsController extends Controller
{
    public function index()
    {
        return DiamondsHelper::getDiamondsFeed();
    }

    private function findManufacturerStatistic($manufacturer, $try = 0)
    {
        try {
            return ImportDiamondStatistic::findOne($manufacturer);
        } catch (\Exception $e) {
            logger($e);

            if ($try > 1) {
                exit;
            }
        }

        sleep(1);

        $try++;

        return $this->findManufacturerStatistic($manufacturer, $try);
    }

    public function store(StoreDiamondPostRequest $request)
    {
        //$statistic = $this->findManufacturerStatistic($request->get('manufacturer'));
        //$statistic->increment('create_request');

        try {
            $diamond = DiamondsHelper::createDiamond($request);
            $this->diamondDebug($diamond, 'create');
            //$statistic->increment('created');

            $response = response()->json(new DiamondResource($diamond));
        } catch (\Exception $exception) {
            //$statistic->increment('create_err');
            logger()->channel('error-create-diamond')->error($exception);

            $response = response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function diamondDebug($diamond, $type)
    {
        logger()->channel('create-update-diamonds')->debug($type . ' diamond manufacturer-' . $diamond->manufacturer_id . ': ' . $diamond->sku . '(' . $diamond->id . ')');
    }

    public function update(UpdateDiamondPostRequest $request, string $sku)
    {
        //$manufacturer = DiamondsHelper::getManufacturerFromSku($sku);
        //$statistic = $this->findManufacturerStatistic($manufacturer);

        //$statistic->increment('update_request');

        try {
            $diamond = DiamondsHelper::updateDiamond($sku, $request);
            $this->diamondDebug($diamond, 'update');
            //$statistic->increment('updated');
            $response = response()->json(new DiamondResource($diamond));
        } catch (\Exception $exception) {
            logger()->channel('error-update-diamond')->error($exception);
            //$statistic->increment('update_err');
            $response = response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function delete(string $sku)
    {
        //$manufacturer = DiamondsHelper::getManufacturerFromSku($sku);
        //$statistic = $this->findManufacturerStatistic($manufacturer);
        //$statistic->increment('delete_request');

        $response = null;

        try {
            $diamond = DiamondsHelper::deleteDiamond($sku);
            if ($diamond) {
                $response = response(['message' => trans('api.catalog.delete')], Response::HTTP_OK);
                //$statistic->increment('deleted');
            }
        } catch (\Exception $exception) {
            logger()->channel('error-del-diamond')->error($exception);
            //$statistic->increment('delete_err');

            $response = response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($response) {
            return $response;
        }
    }

    public function show($id)
    {
        $diamond = Diamond::withCalculatedPrice()
            ->withResourceRelation()
            ->where('enabled', 1)
            ->where('id', $id)
            ->first();

        if ($diamond) {
            // CommonHelper::addToViewed($id, 'diamonds');
            return new DiamondResource($diamond);
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function getFilters()
    {
        $filters = DiamondsHelper::getFilters();

        return $filters;
    }

    public function shapesBanner()
    {
        return DiamondsHelper::shapesBanner();
    }

    public function productCategories()
    {
        return DiamondsHelper::productCategories();
    }

    public function productCategoriesSuggested()
    {
        return DiamondsHelper::productCategoriesSuggested();
    }

    public function similarItems($id)
    {
        return DiamondsHelper::similarItems($id);
    }

    public function recommendDiamonds($chosen_ring_id)
    {
        $ring = EngagementRing::find($chosen_ring_id);
        if ($ring) {
            $shape_id = $ring->stoneShape->id;
            $ring_carat = $ring->stone_size;
            $diamond_carat_range = ConstructorHelper::findSuitableDiamondCarat($ring_carat);

            return DiamondsHelper::recommendDiamonds($shape_id, $diamond_carat_range);
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function presenceList($manufacturer)
    {
        $manufacturerId = Manufacturer::query()->where('slug', $manufacturer)->pluck('id')->first();

        return response()->json(Diamond::query()
            ->select('sku', 'raw_price')
            ->where('manufacturer_id', $manufacturerId)
            ->get()
            ->keyBy('sku')
            ->map(function($item) {
                return ['raw_price' => $item['raw_price']];
            })->toArray());
    }
}