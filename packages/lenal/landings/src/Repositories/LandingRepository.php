<?php

namespace lenal\landings\Repositories;


use Illuminate\Http\Response;
use lenal\catalog\Collections\EngagementRingFeedCollection;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Resources\DiamondResource;
use lenal\landings\Models\Landing;

class LandingRepository
{
    public function fetchAll($landingSlug)
    {
        /** @var Landing $landing */
        $landing = Landing::query()->where('slug', $landingSlug)->first();

        if (!$landing) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        $rings = $this->fetchRings($landing->ringAssign->pluck('ring_id'));
        $diamonds = $this->fetchDiamonds($landing->diamondAssign->pluck('diamond_id'));

        return response()->json([
            'slug' => $landing->slug,
            'header' => $landing->header,
            'image' => $landing->image(),
            'meta_title' => $landing->meta_title,
            'meta_description' => $landing->meta_description,
            'meta_keywords' => $landing->meta_keywords,
            'rings' => (new EngagementRingFeedCollection($rings))->toArray(),
            'diamonds' => DiamondResource::collection($diamonds),
        ]);
    }

    protected function fetchRings($ids)
    {
        return EngagementRing::query()
            ->withCalculatedPrice()
            ->whereIn('id', $ids)
            ->get();
    }

    protected function fetchDiamonds($ids)
    {
        return Diamond::query()
            ->withCalculatedPrice()
            ->whereIn('id', $ids)
            ->get();
    }
}