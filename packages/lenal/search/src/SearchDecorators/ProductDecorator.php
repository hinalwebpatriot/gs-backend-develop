<?php

namespace lenal\search\SearchDecorators;


use App\Traits\ConfigurableTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use lenal\catalog\Collections\ProductFeedCollection;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Resources\DiamondResource;
use lenal\catalog\Resources\ProductResource;
use lenal\search\Contracts\SearchDecorator;
use lenal\search\Facades\SearchHelper;

class ProductDecorator implements SearchDecorator
{
    use ConfigurableTrait;
    /**
     * @var string|Product
     */
    private $modelClass;
    private $categoryId;

    public function __construct(string $model_class, array $params = [])
    {
        $this->modelClass = $model_class;

        $this->initProperties($params);
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function search(string $searchQuery, int $perPage = 50): LengthAwarePaginator
    {
        /** @var \Illuminate\Database\Eloquent\Collection|LengthAwarePaginator $productCollection */
        $productCollection = SearchHelper::search($this->modelClass, $searchQuery, $perPage, ['category_id' => $this->categoryId]);

        $result = $this->modelClass::query()
            ->withResourceRelation()
            ->withCalculatedPrice()
            ->where('category_id', $this->categoryId)
            ->where('is_active', true)
            ->whereIn('id', $productCollection->pluck('id'))
            ->limit($perPage)
            ->get();

        return (new \Illuminate\Pagination\LengthAwarePaginator(
            $result,
            $productCollection->total(),
            $perPage,
            $productCollection->currentPage()
        ));
    }

    public function withModelResource(Model $model)
    {
        return new ProductResource($model);
    }

    public function withModelCollection(Collection $collection)
    {
        return (new ProductFeedCollection($collection))->resolve();
    }

    /**
     * @param array $ids
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchIds($ids, $perPage = 50): LengthAwarePaginator
    {
        return $this->modelClass::query()
            ->selectRaw(implode(',', ['id AS group_sku', 'GROUP_CONCAT(`id`) as ids']))
            ->whereIn('id', $ids)
            ->groupBy('id')
            ->paginate($perPage);
    }

    public function getConditions(): array
    {
        return ['category_id' => $this->categoryId];
    }
}
