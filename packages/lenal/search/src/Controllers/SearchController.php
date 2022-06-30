<?php

namespace lenal\search\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use lenal\search\Resources\DetailSearchCollection;
use lenal\search\Resources\PreviewSearchResource;
use lenal\search\Facades\SearchHelper;

/**
 * Class SearchController
 *
 * @package lenal\search\Controllers
 */
class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function previewSearch(Request $request)
    {
        if (SearchHelper::isEmpty($request)) {
            return response()
                ->json([
                    'message' => 'Search query cannot be empty'
                ], Response::HTTP_BAD_REQUEST);
        }

        return response()
            ->json(new PreviewSearchResource(SearchHelper::quickSearch($request)));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countSearch(Request $request)
    {
        if (SearchHelper::isEmpty($request)) {
            return response()
                ->json([
                    'message' => 'Search query cannot be empty'
                ], Response::HTTP_BAD_REQUEST);
        }

        return response()
            ->json(SearchHelper::countSearch($request));
    }

    /**
     * @param Request $request
     * @param string  $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailSearch(Request $request, string $model)
    {
        $search_data = SearchHelper::detailSearch($request, $model);

        return response()
            ->json(new DetailSearchCollection($search_data));
    }
}
