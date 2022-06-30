<?php

namespace lenal\search\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SearchDecorator
{
    public function __construct(string $model_class, array $params = []);

    public function getModelClass(): string;

    public function search(string $searchQuery, int $perPage = 50): LengthAwarePaginator;

    public function withModelResource(Model $model);

    public function withModelCollection(Collection $collection);

    public function searchIds(array $ids, int $perPage = 50): LengthAwarePaginator;

    public function getConditions(): array;
}
