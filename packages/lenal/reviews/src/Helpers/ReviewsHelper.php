<?php

namespace lenal\reviews\Helpers;

use Illuminate\Database\Eloquent\Builder;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\reviews\Models\Review;
use lenal\reviews\Requests\CreateReviewRequest;
use lenal\reviews\Resources\ReviewResource;
use lenal\reviews\Resources\ReviewWithProductResource;

class ReviewsHelper
{
    const PERPAGE_DEFAULT = 5;

    public function createReviewFormRequest(CreateReviewRequest $request)
    {
        if (($review = Review::create($request->validated()))&& request('photos')) {
            $review->addMultipleMediaFromRequest(['photos'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('photos');
                });
        }
        return response(null);
    }

    public function getSiteReviews()
    {
        return  ReviewResource::collection(
            Review
                ::where(['is_active' => true, 'product_id' => null])
                ->latest()
                ->paginate(self::PERPAGE_DEFAULT)
        );
    }

    public function getProductsReviews()
    {
        return ReviewWithProductResource::collection(
            Review
                ::where('product_id', '!=', null)
                ->where('is_active', true)
                ->latest()
                ->paginate(self::PERPAGE_DEFAULT)
        );
    }

    public function getDiamondReviews($id = null)
    {
        return $id
            ? ReviewResource::collection(
                    Review
                        ::where(['is_active' => true, 'product_id'=> $id, 'product_type' => Diamond::class])
                        ->latest()
                        ->paginate(self::PERPAGE_DEFAULT)
                )
            : ReviewWithProductResource::collection(
                Review
                    ::where(['is_active' => true, 'product_type' => Diamond::class])
                    ->latest()
                    ->paginate(self::PERPAGE_DEFAULT)
            );
    }

    public function getEngagementsReviews($id = null)
    {
        return $id
            ? ReviewResource::collection(
                    Review
                        ::where(['is_active' => true, 'product_id'=> $id, 'product_type' => EngagementRing::class])
                        ->latest()
                        ->paginate(self::PERPAGE_DEFAULT)
                )
            : ReviewWithProductResource::collection(
                Review
                    ::where(['is_active' => true, 'product_type' => EngagementRing::class])
                    ->latest()
                    ->paginate(self::PERPAGE_DEFAULT)
            );
    }

    public function getWeddingsReviews($id = null)
    {
        return  $id
            ? ReviewResource::collection(
                    Review
                        ::where(['is_active' => true, 'product_id'=> $id, 'product_type' => WeddingRing::class])
                        ->latest()
                        ->paginate(self::PERPAGE_DEFAULT)
                )
            : ReviewWithProductResource::collection(
                    Review
                        ::where(['is_active' => true, 'product_type' => WeddingRing::class])
                        ->latest()
                        ->paginate(self::PERPAGE_DEFAULT)
                );
    }

    public function getCatalogProductReviews($id = null)
    {
        $result = Review::query()
            ->where(['is_active' => true, 'product_type' => Product::class])
            ->where(function(Builder $query) use ($id) {
                if ($id) {
                    $query->where('product_id', $id);
                }
            })
            ->latest()
            ->paginate(self::PERPAGE_DEFAULT);

        return $id > 0 ? ReviewResource::collection($result) : ReviewWithProductResource::collection($result);
    }

    public function getProductRate($item)
    {
        $reviews = Review
                    ::where(['is_active' => true, 'product_id'=> $item->id, 'product_type' => get_class($item)])
                    ->get();
        return [
            'average' => intval(round($reviews->avg('rate'), 0)),
            'count' => $reviews->count()
        ];
    }
}
