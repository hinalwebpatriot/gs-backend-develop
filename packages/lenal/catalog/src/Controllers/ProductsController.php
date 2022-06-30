<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Repositories\ProductCategoryRepository;
use lenal\catalog\Repositories\ProductFilterRepository;
use lenal\catalog\Resources\CatalogProductComplexResource;
use lenal\catalog\Resources\CategoryResource;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Repositories\ProductRepository;


class ProductsController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $products;

    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    public function index($category)
    {
        if (!Category::query()->where('slug', $category)->exists()) {
            return response()->json([
                'data' => [],
            ],  Response::HTTP_NOT_FOUND);
        }

        return response()->json($this->products->feed($category));
    }

    public function categories()
    {
        $repo = app(ProductCategoryRepository::class);
        return response()->json(CategoryResource::collection($repo->getList()));
    }

    public function filters($category)
    {
        $filterRepo = app(ProductFilterRepository::class);
        $categoryRepo = app(ProductCategoryRepository::class);
        $categoryModel = $categoryRepo->getBySlug($category);
        if (!$categoryModel) {
            return response()->json([
                'error' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($filterRepo->categoryFilters($categoryModel, $categoryRepo->getList()));
    }

    public function show($id)
    {
        $product = $this->products->product($id);

        if (!$product) {
            return response()->json([
                'message' => trans('api.error.not_found'),
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        CommonHelper::addToViewed($id, 'products');

        return response()->json(new CatalogProductComplexResource($product));
    }
}