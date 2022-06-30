<?php

namespace lenal\search\Helpers;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Scout\Builder;
use lenal\blog\Models\Article;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\search\Contracts\SearchDecorator;
use lenal\search\SearchDecorators\BlogDecorator;
use lenal\search\SearchDecorators\DiamondDecorator;
use lenal\search\SearchDecorators\ProductDecorator;
use lenal\search\SearchDecorators\RingDecorator;

/**
 * Class SearchHelper
 *
 * @package lenal\reviews\Helpers
 */
class SearchHelper
{
    public $productQueryParams = ['engagement'];
    const MAX_LIMIT = 1000;

    /**
     * @param  null|string  $model_name
     *
     * @return array|SearchDecorator|null
     */
    public function getModelDecorators(?string $model_name = null)
    {
        $decorators = [
            'blog'       => new BlogDecorator(Article::class),
            'diamond'    => new DiamondDecorator(Diamond::class),
            'engagement' => new RingDecorator(EngagementRing::class),
            'wedding'    => new RingDecorator(WeddingRing::class),
        ];

        foreach (Category::asArray() as $category) {
            $decorators[$category['slug']] = new ProductDecorator(Product::class, ['categoryId' => $category['id']]);
        }

        return key_exists($model_name, $decorators)
            ? $decorators[$model_name]
            : $decorators;
    }

    /**
     * @param  Request  $request
     *
     * @return bool
     */
    public function isEmpty(Request $request)
    {
        return empty($request->get('q')) && !$request->only($this->productQueryParams);
    }

    /**
     * @param  Request  $request
     *
     * @return Collection
     * @throws \Exception
     */
    public function quickSearch(Request $request)
    {
        $search_request = $request->get('q');

        return collect($this->getModelDecorators())
            ->mapWithKeys(function (SearchDecorator $model_decorator, $model_name) use ($search_request) {
                return [
                    $model_name => $this->search(
                        $model_decorator->getModelClass(),
                        $search_request,
                        4,
                        $model_decorator->getConditions()
                    )
                ];
            });
    }

    /**
     * @param  Request  $request
     * @param  string   $model_name
     *
     * @return LengthAwarePaginator|null
     * @throws \Exception
     */
    public function detailSearch(Request $request, string $model_name): ?LengthAwarePaginator
    {
        /** @var SearchDecorator|null $model_decorator */
        $model_decorator = $this->getModelDecorators($model_name);

        if (is_null($model_decorator)) {
            return null;
        }

        $per_page_request = $request->get('per_page') ?: 50;
        $per_page = $per_page_request > self::MAX_LIMIT ? self::MAX_LIMIT : $per_page_request;

        if ($request->get($model_name)) {
            return $model_decorator->searchIds(explode(',', $request->get($model_name)), $per_page);
        } else {
            return $model_decorator->search($request->get('q'), $per_page);
        }
    }

    /**
     * @param  Request  $request
     *
     * @return array|Collection
     * @throws \Exception
     */
    public function countSearch(Request $request)
    {
        $search_request = $request->get('q');
        $products = $request->only($this->productQueryParams);

        if ($search_request && !$products) {
            return collect($this->getModelDecorators())
                ->map(function (SearchDecorator $model_decorator) use ($search_request) {
                    $collection = $model_decorator
                        ->search($search_request);

                    return $collection instanceof LengthAwarePaginator
                        ? $collection->total()
                        : $collection->count();
                });
        } else {
            return collect($this->getModelDecorators())->map(function ($value, $key) use ($products) {
                return $this->countProducts($products, $key);
            });
        }

    }

    private function countProducts($products, $key)
    {
        return isset($products[$key]) ? count(explode(',', $products[$key] ?? '')) : 0;
    }

    /**
     * @param  string    $model
     * @param  string    $search_query
     * @param  int|null  $limit
     *
     * @param  array     $conditions
     * @return mixed
     * @throws \Exception
     */
    public function search(string $model, string $search_query, ?int $limit = null, array $conditions = [])
    {
        if (!class_exists($model)) {
            throw new \Exception("Model class $model does not exist.");
        }

        /** @var Builder $search_builder */
        $search_builder = $model::search($search_query);

        if ($conditions) {
            foreach ($conditions as $key => $value) {
                $search_builder->where($key, $value);
            }
        }

        switch ($model) {
            case EngagementRing::class:
            case WeddingRing::class:
            case Product::class:
                $search_builder->query(function ($query) use ($conditions) {
                    $query->where('is_active', true)
                        ->withResourceRelation()
                        ->withCalculatedPrice();
                    if ($conditions) {
                        foreach ($conditions as $key => $value) {
                            $query->where($key, $value);
                        }
                    }
                });
                break;
            case Diamond::class:
                $search_builder->query(function ($query) use ($conditions) {
                    $query->where('enabled', true)
                        ->whereIn('has_imported', [0, 1])
                        ->withResourceRelation()
                        ->withCalculatedPrice();
                    if ($conditions) {
                        foreach ($conditions as $key => $value) {
                            $query->where($key, $value);
                        }
                    }
                });
                break;
        }

        if (is_null($limit)) {
            return $search_builder->get();
        }

        return $search_builder->paginate($limit > self::MAX_LIMIT ? self::MAX_LIMIT : $limit);
    }

    /**
     * @param  Model|null  $model
     *
     * @return null|mixed
     */
    public function wrapViaResource(?Model $model)
    {
        $model_class = get_class($model);
        /** @var SearchDecorator $model_decorator */
        $model_decorator = $this->getDecoratorByModel($model_class);

        return $model_decorator instanceof SearchDecorator
            ? $model_decorator->withModelResource($model)
            : null;
    }

    /**
     * @param  Collection  $collection
     *
     * @return mixed
     */
    public function wrapViaCollection(Collection $collection)
    {
        if ($collection->count() == 0) {
            return [];
        }

        $model_class = get_class($collection->first());
        /** @var SearchDecorator $model_decorator */
        $model_decorator = $this->getDecoratorByModel($model_class);

        return $model_decorator instanceof SearchDecorator
            ? $model_decorator->withModelCollection($collection)
            : [];
    }

    /**
     * @param  string  $model_class
     *
     * @return SearchDecorator|null
     */
    private function getDecoratorByModel(string $model_class): ?SearchDecorator
    {
        return collect($this->getModelDecorators())
            ->filter(function (SearchDecorator $model_decorator) use ($model_class) {
                return $model_decorator->getModelClass() == $model_class;
            })
            ->first();
    }
}
