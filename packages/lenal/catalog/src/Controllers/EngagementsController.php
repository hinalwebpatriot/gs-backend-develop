<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use lenal\catalog\DTO\EngagementRingsFilterDTO;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Facades\EngagementsHelper;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Repositories\EngagementRingRepository;
use lenal\catalog\Requests\EngagementFeedFiltersRequest;
use lenal\catalog\Requests\StoreEngagementPostRequest;
use lenal\catalog\Requests\UpdateEngagementPutRequest;
use lenal\catalog\Resources\EngagementRingGroupCollection;
use lenal\catalog\Resources\EngagementRingGroupResource;
use lenal\catalog\Resources\EngagementRingResource;
use lenal\catalog\Resources\EngagementRings\FeedCollection;

class EngagementsController extends Controller
{
    private EngagementRingRepository $repository;

    public function __construct(EngagementRingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return EngagementRingGroupCollection
     */
    public function index(): EngagementRingGroupCollection
    {
        /*$key = hash('sha256',json_encode(request()->toArray()));
        if (strlen($key) === 64) {
            return Cache::remember($key, 5, function () {
                return EngagementsHelper::getEngagementRings();
            });Engag
        }*/
        
        return EngagementsHelper::getEngagementRings();
    }

    public function feed(EngagementFeedFiltersRequest $request): FeedCollection
    {
        $filters = EngagementRingsFilterDTO::loadFromRequest($request);
        $list = $this->repository->list($filters);
        return new FeedCollection($list);
    }



    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $engagement = EngagementsHelper::getEngagementRing($id);

        if (is_null($engagement)) {
            return response()->json([
                'message' => trans('api.error.not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        CommonHelper::addToViewed($id, 'engagement-rings');

        return response()
            ->json(new EngagementRingGroupResource($engagement));
    }

    /**
     * @param StoreEngagementPostRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreEngagementPostRequest $request): JsonResponse
    {
        $engagement = EngagementsHelper::createEngagement($request);

        return $engagement
            ? response()->json(new EngagementRingResource($engagement))
            : response()->json(null);
    }

    /**
     * @param UpdateEngagementPutRequest $request
     * @param string                     $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEngagementPutRequest $request, string $sku)
    {
        try {
            $engagement = EngagementsHelper::updateEngagement($sku, $request);

            return response()
                ->json(new EngagementRingResource($engagement));
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getFilters(): JsonResponse
    {
        return response()->json(EngagementsHelper::getFilters());
    }

    public function engagementRingsForDiamondFeed()
    {
        $rings = EngagementRing
            /*::select(DB::raw('tmp.*'))
            ->leftJoin(DB::raw('(' . EngagementRing::withCalculatedPrice()->inRandomOrder()->toSql() . ') tmp'), 'engagement_rings.item_name', 'tmp.item_name')
            ->groupBy('tmp.item_name')*/
            ::withCalculatedPrice()
            ->withResourceRelation()
            ->inRandomOrder()
            ->take(10)
            ->get();

        return EngagementRingResource::collection($rings);
    }
}