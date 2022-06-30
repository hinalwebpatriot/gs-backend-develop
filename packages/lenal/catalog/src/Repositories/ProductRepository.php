<?php

namespace lenal\catalog\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use lenal\catalog\Collections\ProductFeedCollection;
use lenal\catalog\Facades\FilterBuilderHelper;
use lenal\catalog\Helpers\SortTypes\SortWithProperty;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Models\Products\ProductStyle;
use lenal\catalog\Models\Rings\Metal;

class ProductRepository
{
    public $appliedFilter = false;
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Product::query();
    }

    /**
     * @param string $category
     * @return array
     */
    public function feed($category)
    {
        $groups = $this->paginateByGroups($category);

        $productsBuilder = $this->query()
            ->scopes(['withCalculatedPrice'])
            ->with(['stoneShape'])
            ->whereIn('id', $this->parseProductIdsFromGroups($groups))
            ->orderByDesc('is_top');

        $sort = request()->input('sort.field', 'price');

        switch ($sort) {
            case 'price':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $productsBuilder,
                        true,
                        'raw_price',
                        request()->input('sort.order', 'asc')
                    ),
                ]);
                break;
            case 'new':
                FilterBuilderHelper::applyOrder([
                    SortWithProperty::make(
                        $productsBuilder,
                        true,
                        'custom_sort',
                        'desc'
                    ),
                    SortWithProperty::make(
                        $productsBuilder,
                        true,
                        'raw_price',
                        'asc'
                    ),
                ]);
                break;
        }

        $products = $productsBuilder->get();


        $productGroupCollection = collect((new ProductFeedCollection($products))->resolve())
            ->groupBy('group_sku');

        $data = [];

        foreach ($groups as $group) {
            /** @var Collection $productCollection */
            $productCollection = $productGroupCollection->get($group->group_sku, []);
            if (!$productCollection) {
                continue;
            }

            $data[] = [
                'group_sku' => $group->group_sku,
                'metals' => $productCollection->mapWithKeys(function($product) {
                    return [
                        $product['options']['metal']['slug'] => $product['options']['metal'],
                    ];
                }),
                'products' => $productCollection,
            ];
        }

        return array_merge(
            ['data' => $data],
            Arr::only((new ResourceCollection($groups))->toResponse(request())->getData(true), ['links', 'meta'])
        );

    }

    private function applyFilters(Builder $builder)
    {
        if (request()->has('metal')) {
            $this->applyArrayFilter($builder, 'metal_id', $this->pluckFilterIds(Metal::class, request('metal')));
        }

        if (request()->has('style')) {
            $this->applyArrayFilter($builder, 'style_id', $this->pluckFilterIds(ProductStyle::class, request('style')));
        }

        if (request()->has('shape')) {
            $this->applyArrayFilter($builder, 'shape_id', $this->pluckFilterIds(Shape::class, request('shape')));
        }

        if (request()->has('brands')) {
            $this->applyArrayFilter($builder, 'brand_id', $this->pluckFilterIds(Brand::class, request('brands')));
        }
    }

    /**
     * @param string|Model $class
     * @param $requestData
     * @return array
     */
    private function pluckFilterIds($class, $requestData)
    {
        $items = array_flip($class::all()->pluck('id', 'slug')->toArray());
        $result = array_intersect($items, $requestData);

        return $result ? array_keys($result) : [];
    }

    private function applyArrayFilter(Builder $builder, $column, $values)
    {
        if ($values) {
            $this->appliedFilter = true;
            $builder->whereIn($column, $values);
        }
    }

    private function applySizeFilter(Builder $builder, $sizes)
    {
        if (!$sizes) {
            return ;
        }

        $this->appliedFilter = true;

        $sizes = collect($sizes);

        $builder->whereHas('minSize', function (Builder $query) use ($sizes) {
            $query->where(function ($query) use ($sizes) {
                $sizes
                    ->each(function ($filter_value) use ($query) {
                        $query->orWhere('slug', '<=', $filter_value);
                    });
            });
        });

        $builder->whereHas('maxSize', function (Builder $query) use ($sizes) {
            $query->where(function ($query) use ($sizes) {
                $sizes
                    ->each(function ($filter_value) use ($query) {
                        $query->orWhere('slug', '>=', $filter_value);
                    });
            });
        });
    }

    /**
     * @param $category
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function paginateByGroups($category)
    {
        /** @var Builder $groupBuilder */
        $groupBuilder = $this->query()
            ->from('products')
            ->withCalculatedPrice(['products.group_sku'])
            ->selectRaw('MAX(is_top) AS is_top,MAX(custom_sort) as custom_sort,GROUP_CONCAT(products.id) as ids')
            ->join('categories AS c', 'c.id', '=', 'products.category_id')
            ->where('c.slug', $category)
            ->where('products.is_active', true)
            ->where(function(Builder $builder) {
                $this->applyFilters($builder);
            });

        $groupBuilder->orderByDesc('is_top');

        if (request()->has('sort')) {
            switch (request()->input('sort.field', 'price')) {
                case 'new':
                    $groupBuilder->orderByDesc('custom_sort');
                    $groupBuilder->orderBy('raw_price');
                    break;
                default:
                    $groupBuilder->orderBy('raw_price', request()->input('sort.order'));
            }
        }


        $groupBuilder->groupBy('group_sku');

        if (request()->has('price')) {
            $this->appliedFilter = true;
            $groupBuilder->having('calculated_price', '>=', request()->input('price.min'));
            $groupBuilder->having('calculated_price', '<=', request()->input('price.max'));
        }

        if (request()->has('size')) {
            $this->applySizeFilter($groupBuilder, request('size'));
        }

        if (request()->has('gender')) {
            $groupBuilder->whereIn('gender', ['neutral', request()->input('gender','female')]);
        }

        return $groupBuilder->paginate(request('per_page', 20));
    }

    /**
     * @param $groups
     * @return array
     */
    private function parseProductIdsFromGroups($groups)
    {
        $productIds = [];
        foreach ($groups as $productGroup) {
            $productIds = array_merge($productIds, explode(',', $productGroup['ids']));
        }

        return $productIds;
    }

    public function product($id)
    {
        return $this->query()
            ->scopes(['withCalculatedPrice'])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @param string $groupSku
     * @return Product[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed[]
     */
    public function fetchByGroupSku($groupSku)
    {
        return $this->query()->scopes(['withCalculatedPrice'])
            ->with(['category', 'brand', 'stoneShape', 'style', 'minSize', 'maxSize', 'metal'])
            ->where('group_sku', $groupSku)
            ->where('is_active', true)
            ->get();
    }

    public function fetchProductSizes($min, $max)
    {
        return ProductSize::query()
            ->orderBy('slug')
            ->whereBetween('slug', [$min, $max])
            ->get();
    }
}