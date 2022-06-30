<?php

namespace lenal\search\SearchDecorators;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Resources\DiamondResource;
use lenal\search\Contracts\SearchDecorator;
use lenal\search\Facades\SearchHelper;

/**
 * Class DiamondDecorator
 *
 * @package lenal\search\SearchDecorators
 */
class DiamondDecorator implements SearchDecorator
{
    /**
     * @var string|Diamond
     */
    private $model_class;

    /**
     * DiamondDecorator constructor.
     *
     * @param string $model_class
     * @param array $params
     */
    public function __construct(string $model_class, array $params = [])
    {
        $this->model_class = $model_class;
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->model_class;
    }

    /**
     * @param string $search_request
     * @param int    $per_page
     *
     * @return LengthAwarePaginator
     */
    public function search(string $search_request, int $per_page = 50): LengthAwarePaginator
    {
        /** @var \Illuminate\Database\Eloquent\Collection|LengthAwarePaginator $diamond_collection */
        $diamond_collection = SearchHelper::search($this->model_class, $search_request, $per_page);

        $diamonds = $this->model_class::query()
            ->withResourceRelation()
            ->withCalculatedPrice()
            ->whereIn('id', $diamond_collection->pluck('id'))
            ->limit($per_page)
            ->get();

        return (new \Illuminate\Pagination\LengthAwarePaginator(
            $diamonds,
            $diamond_collection->total(),
            $per_page,
            $diamond_collection->currentPage()
        ));
    }

    /**
     * @param array $ids
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function searchIds($ids, $per_page = 50): LengthAwarePaginator
    {
        return $this->model_class::query()
            ->scopes(['withResourceRelation'])
            ->withCalculatedPrice()
            ->whereIn('id', $ids)
            ->paginate($per_page);
    }

    /**
     * @param Model $model
     *
     * @return DiamondResource
     */
    public function withModelResource(Model $model)
    {
        return new DiamondResource($model);
    }

    /**
     * @param Collection $collection
     *
     * @return AnonymousResourceCollection
     */
    public function withModelCollection(Collection $collection)
    {
        return DiamondResource::collection($collection);
    }

    public function getConditions(): array
    {
        return [];
    }
}
