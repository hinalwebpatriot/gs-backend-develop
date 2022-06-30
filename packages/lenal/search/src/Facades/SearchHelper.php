<?php

namespace lenal\search\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use lenal\search\Contracts\SearchDecorator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class SearchHelper
 *
 * @package lenal\search\Facades
 * @method static getModelDecorators(?string $model_name = null): array|SearchDecorator
 * @method static isEmpty(Request $request): bool
 * @method static quickSearch(Request $request): Collection
 * @method static countSearch(Request $request): Collection
 * @method static search(string $model, string $search_query, ?int $limit = null, array $conditions = []): Collection|LengthAwarePaginator
 * @method static wrapViaResource(?Model $model)
 * @method static wrapViaCollection(Collection $collection)
 * @method static detailSearch(Request $request, string $model_name): ?LengthAwarePaginator
 *
 * @mixin \lenal\search\Helpers\SearchHelper
 */
class SearchHelper extends Facade {

    public static function getFacadeAccessor()
    {
        return 'algolia-search';
    }
}
