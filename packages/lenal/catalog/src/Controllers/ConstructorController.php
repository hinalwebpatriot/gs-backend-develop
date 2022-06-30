<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use lenal\catalog\Facades\ConstructorHelper;
use lenal\catalog\Facades\DiamondsHelper;
use lenal\catalog\Facades\EngagementsHelper;
use lenal\catalog\Models\CompleteRing;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Requests\ConstructorParamsRequest;
use lenal\catalog\Requests\ConstructorUpdateRequest;
use lenal\catalog\Resources\CompleteRingResource;
use Symfony\Component\HttpFoundation\Request;

class ConstructorController extends Controller
{
    public function getSuitableRings($chosen_diamond_id)
    {
        $diamond = Diamond::find($chosen_diamond_id);
        if ($diamond) {
            return EngagementsHelper::getEngagementRings($diamond->shape->id, $diamond->carat);
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function getSuitableDiamonds($chosen_ring_id)
    {
        if ($ring = EngagementRing::query()->find($chosen_ring_id)) {
            return DiamondsHelper::getDiamondsForConstructor($ring);
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function matchProducts(Request $request)
    {
        $diamond = Diamond::find(request('diamond_id'));
        $ring = EngagementRing::find(request('ring_id'));

        if ($diamond && $ring) {
            return ConstructorHelper::matchProducts($diamond, $ring);
        }

        return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
    }

    public function saveCompleteRing(ConstructorParamsRequest $request)
    {
        return ConstructorHelper::saveCompletedRing(
            request('diamond_id'), request('ring_id'), request('ring_size_slug'), request('engraving')
        );
    }

    public function getCompleteRings()
    {
        return ConstructorHelper::getCompleteRings();
    }

    public function deleteCompleteRing(ConstructorUpdateRequest $request)
    {
        return ConstructorHelper::deleteCompleteRing(request('id'));
    }

    public function updateCompleteRing(ConstructorUpdateRequest $request)
    {
        return ConstructorHelper::updateCompleteRing(
            request('id'), request('diamond_id'), request('ring_id'), request('ring_size_slug'), request('engraving')
        );
    }

    public function addToCart(ConstructorUpdateRequest $request)
    {
        return ConstructorHelper::addToCart(request('id'));
    }

}