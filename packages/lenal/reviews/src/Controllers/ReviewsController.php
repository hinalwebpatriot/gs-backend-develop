<?php

namespace lenal\reviews\Controllers;

use App\Http\Controllers\Controller;
use lenal\reviews\Facades\ReviewsHelper;
use lenal\reviews\Requests\CreateReviewRequest;

class ReviewsController extends Controller
{

    public function addReview(CreateReviewRequest $request)
    {
        return ReviewsHelper::createReviewFormRequest($request);
    }

    public function getSiteReviews()
    {
        return ReviewsHelper::getSiteReviews();
    }

    public function getProductsReviews()
    {
        return ReviewsHelper::getProductsReviews();
    }

    public function getDiamondReviews(int $id = null)
    {
        return ReviewsHelper::getDiamondReviews($id);
    }

    public function getEngagementsReviews(int $id = null)
    {
        return ReviewsHelper::getEngagementsReviews($id);
    }

    public function getWeddingsReviews(int $id = null)
    {
        return ReviewsHelper::getWeddingsReviews($id);
    }

    public function getCatalogProductReviews(int $id = null)
    {
        return ReviewsHelper::getCatalogProductReviews($id);
    }
}
