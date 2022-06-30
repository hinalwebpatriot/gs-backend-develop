<?php

namespace lenal\search\SearchDecorators;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use lenal\blog\Models\Article;
use lenal\blog\Resources\ArticlePreviewResource;
use lenal\search\Contracts\SearchDecorator;
use lenal\search\Facades\SearchHelper;

/**
 * Class BlogDecorator
 *
 * @package lenal\search\SearchDecorators
 */
class BlogDecorator implements SearchDecorator
{
    /**
     * @var string|Article
     */
    private $model_class;

    /**
     * BlogDecorator constructor.
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
        return SearchHelper::search($this->model_class, $search_request, $per_page);
    }

    /**
     * @param Model $model
     *
     * @return ArticlePreviewResource
     */
    public function withModelResource(Model $model)
    {
        return new ArticlePreviewResource($model);
    }

    /**
     * @param Collection $collection
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function withModelCollection(Collection $collection)
    {
        return ArticlePreviewResource::collection($collection);
    }

    /**
     * @param array $ids
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function searchIds($ids, $per_page = 50): LengthAwarePaginator
    {
        return $this->model_class::query()
            ->whereIn('id', $ids)
            ->paginate($per_page);
    }

    public function getConditions(): array
    {
        return [];
    }
}
