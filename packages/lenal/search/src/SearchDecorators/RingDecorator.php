<?php

namespace lenal\search\SearchDecorators;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use lenal\catalog\Collections\EngagementRingFeedCollection;
use lenal\catalog\Collections\WeddingRingFeedCollection;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Resources\EngagementRingResource;
use lenal\catalog\Resources\WeddingRingResource;
use lenal\search\Contracts\SearchDecorator;
use lenal\search\Facades\SearchHelper;

/**
 * Class RingDecorator
 *
 * @package lenal\search\SearchDecorators
 */
class RingDecorator implements SearchDecorator
{
    /**
     * @var string|WeddingRing|EngagementRing
     */
    private $model_class;
    /**
     * @var array
     */
    private $params;

    /**
     * RingDecorator constructor.
     *
     * @param string $model_class
     */
    public function __construct(string $model_class, array $params = [])
    {
        $this->model_class = $model_class;
        $this->params = $params;
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
        /** @var \Illuminate\Database\Eloquent\Collection|LengthAwarePaginator $ring_collection */
        $ring_collection = SearchHelper::search($this->model_class, $search_request, $per_page);

        $result = $this->model_class::query()
            ->withResourceRelation()
            ->withCalculatedPrice()
            ->whereIn('id', $ring_collection->pluck('id'))
            ->where('is_active', true)
            ->limit($per_page)
            ->get();

        return (new \Illuminate\Pagination\LengthAwarePaginator(
            $result,
            $ring_collection->total(),
            $per_page,
            $ring_collection->currentPage()
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
            ->selectRaw(implode(',', ['id AS group_sku', 'GROUP_CONCAT(`id`) as ids']))
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->groupBy('id')
            ->paginate($per_page);
    }

    /**
     * @param Model $model
     *
     * @return EngagementRingResource|WeddingRingResource|null
     */
    public function withModelResource(Model $model)
    {
        switch ($this->model_class) {
            case EngagementRing::class:
                return new EngagementRingResource($model);
            case WeddingRing::class:
                return new WeddingRingResource($model);
            default:
                return null;
        }
    }

    /**
     * @param Collection $collection
     *
     * @return array|null
     */
    public function withModelCollection(Collection $collection)
    {
        switch ($this->model_class) {
            case EngagementRing::class:
                return (new EngagementRingFeedCollection($collection))->toArray();
            case WeddingRing::class:
                return (new WeddingRingFeedCollection($collection))->toArray();
        }

        return null;
    }

    public function getConditions(): array
    {
        return [];
    }
}
