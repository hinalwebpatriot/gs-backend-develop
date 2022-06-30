<?php

namespace App\Services;


use Foolz\SphinxQL\Drivers\Mysqli\ResultSetAdapter;
use Foolz\SphinxQL\Drivers\ResultSet;
use Foolz\SphinxQL\Drivers\ResultSetInterface;
use Foolz\SphinxQL\Helper;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Mockery\Exception;

class SphinxEngine extends \Laravel\Scout\Engines\Engine
{
    /**
     * @var SphinxQL
     */
    protected $sphinx;
    protected $rtSuffix;
    /**
     * @var array
     */
    protected $whereIns = [];

    public function __construct($sphinx)
    {
        $this->sphinx = $sphinx;
        $this->rtSuffix = config('scout.sphinx.rtSuffix');
    }

    public function update($models)
    {
        return false;
        if ($models->isEmpty()) {
            return;
        }

        $models->each(function ($model) {
            /** @var Model|Searchable $model */

            if (isset($model->isRT) && $searchableData = $model->toSearchableArray()) {
                $searchableData['id'] = (int) $model->getKey();
                $columns = array_keys($searchableData);

                $searchableData = array_map(function($item) {
                    return str_replace(['’'], "'", $item);
                }, $searchableData);

                try {
                    $this->sphinx
                        ->replace()
                        ->into($this->rtIndex($model->searchableAs()))
                        ->columns($columns)
                        ->values($searchableData)
                        ->execute();
                } catch (\Exception $e) {
                    $this->log($e->getMessage());
                }
            }
        });
    }

    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $models->each(function ($model) {
            /** @var Model|Searchable $model */

            if (isset($model->isRT)) {
                try {
                    $this->sphinx
                        ->delete()
                        ->from($this->rtIndex($model->searchableAs()))
                        ->where('id', '=', $model->getKey())
                        ->execute();
                } catch (\Exception $e) {
                    $this->log($e->getMessage());
                }
            }
        });
    }

    private function rtIndex($index)
    {
        return $index . $this->rtSuffix;
    }

    /**
     * @param Builder $builder
     * @return mixed
     * @throws \Foolz\SphinxQL\Exception\ConnectionException
     * @throws \Foolz\SphinxQL\Exception\DatabaseException
     * @throws \Foolz\SphinxQL\Exception\SphinxQLException
     */
    public function search(Builder $builder)
    {
        try {
            return $this->performSearch($builder)->execute();
        } catch (Exception $e) {
            $this->log($e->getMessage());
        }
    }

    public function paginate(Builder $builder, $perPage, $page)
    {
        try {
            return $this->performSearch($builder)
                ->limit($perPage * ($page - 1), $perPage)
                ->execute();
        } catch (Exception $e) {
            $this->log($e->getMessage());
        }

        return [];
    }

    public function mapIds($results)
    {
        return collect($results->fetchAllAssoc())->pluck('id')->values();
    }

    /**
     * @param Builder $builder
     * @param mixed $results
     * @param Model|Searchable $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        if ($results->count() === 0) {
            return $model->newCollection();
        }

        $objectIds = collect($results->fetchAllAssoc())->pluck('id')->values()->all();

        $objectIdPositions = array_flip($objectIds);

        return $model
            ->getScoutModelsByIds($builder, $objectIds)
            ->filter(function (/** @var Searchable $model */ $model) use ($objectIds) {
                return in_array($model->getScoutKey(), $objectIds);
            })
            ->sortBy(function (/** @var Searchable $model */ $model) use ($objectIdPositions) {
                return $objectIdPositions[$model->getScoutKey()];
            })
            ->values();
    }

    public function getTotalCount($results)
    {
        $res = (new Helper($this->sphinx->getConnection()))->showMeta()->execute();
        $assoc = $res->fetchAllAssoc();
        $totalCount = $results->count();

        foreach ($assoc as $item => $value) {
            if ($value["Variable_name"] == "total_found") {
                $totalCount = $value["Value"];
            }
        }

        return $totalCount;
    }

    /**
     * @param Model|Searchable $model
     * @throws \Foolz\SphinxQL\Exception\ConnectionException
     * @throws \Foolz\SphinxQL\Exception\DatabaseException
     * @throws \Foolz\SphinxQL\Exception\SphinxQLException
     */
    public function flush($model)
    {
        if (isset($model->isRT)) {
            try {
                (new Helper($this->sphinx->getConnection()))
                    ->truncateRtIndex($model->searchableAs())
                    ->execute();
            } catch (\Exception $e) {
                $this->log($e);
            }
        }
    }

    protected function performSearch(Builder $builder)
    {
        /**
         * @var Searchable $model
         */
        $model = $builder->model;
        $phrase = '"' . $builder->query . '"';
        $hasSpace = false;

        if (strpos(trim($builder->query), ' ') !== false) {
            $phrase .= '/1';
            $hasSpace = true;
        }

        $query = $this->sphinx
            ->select('*', SphinxQL::expr('WEIGHT() AS weight'))
            ->from($model->searchableAs());

        if (!$hasSpace && $this->isSku($builder->query) && $model->searchableAs() != 'blog') {
            $query->where('sku', '=', trim($builder->query));
        } else {
            $query->match('*', SphinxQL::expr($phrase));
        }

        foreach ($builder->wheres as $clause => $filters) {
            $query->where($clause, '=', $filters);
        }

        foreach ($this->whereIns as $whereIn) {
            $query->where(key($whereIn), 'IN', $whereIn[key($whereIn)]);
        }

        if ($builder->callback) {
            call_user_func($builder->callback, $query);
        }

        if (empty($builder->orders)) {
            $query->orderBy('weight', 'DESC');
        } else {
            foreach ($builder->orders as $order) {
                $query->orderBy($order['column'], $order['direction']);
            }
        }

        return $query;
    }

    private function isSku($word)
    {
        $word = (string) $word;
        $codes = array_values(config('import.stock_number_prefix'));
        $codes[] = 'GSD';
        $codes[] = 'IN';

        $wordLen = strlen($word);

        foreach ($codes as $code) {
            $codeLen = strlen($code);
            if ($code == substr($word, 0, $codeLen) && $wordLen > $codeLen + 3) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $attribute
     * @param array $arrayIn
     */
    public function addWhereIn(string $attribute, array $arrayIn)
    {
        $this->whereIns[] = array($attribute => $arrayIn);
    }

    private function log($message)
    {
        logger()->channel('search')->error($message);
    }

}