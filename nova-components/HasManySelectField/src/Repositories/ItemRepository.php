<?php

namespace HasManySelectField\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class ItemRepository
{
    /**
     * @var Request
     */
    private $request;
    private $resourceId;
    /**
     * @var string|Model
     */
    private $resourceModel;
    /**
     * @var string|Model
     */
    private $itemModel;
    private $itemRelated;
    /**
     * @var string|\Illuminate\Http\Resources\Json\JsonResource
     */
    private $resourceFormat;
    private $searchColumn;

    public function __construct(Request $request)
    {
        $this->request = $request;

        foreach ($request->all() as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = urldecode($value);
            }
        }
    }

    public function getItems()
    {
        return $this->itemRelated()->map(function($model) {
            return $this->format($model);
        });
    }

    public function getItem($itemId)
    {
        return $this->format($this->itemRelatedMethod()->find($itemId));
    }

    public function detachItem($itemId)
    {
        return $this->itemRelatedMethod()->detach($itemId);
    }

    public function attachItem($itemId)
    {
        if ($this->itemRelated()->contains($itemId)) {
            throw new \Exception('Item already added!');
        }

        return $this->itemRelatedMethod()->attach($itemId);
    }

    public function format($model)
    {
        return new $this->resourceFormat($model);
    }

    /**
     * @return Builder
     */
    protected function builder()
    {
        return $this->resourceModel::query();
    }

    protected function itemRelated()
    {
        $model = $this->getModel();

        if (!$model) {
            return collect();
        }

        return $model->{$this->itemRelated};
    }

    protected function itemRelatedMethod()
    {
        return $this->getModel()->{$this->itemRelated}();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getModel()
    {
        return $this->builder()->find($this->resourceId);
    }

    public function search()
    {
        $search = $this->request->get('search');
        if (!$search) {
            return [];
        }

        return $this->itemModel::query()
            ->where($this->searchColumn, 'like', e($search) . '%')
            ->limit(15)
            ->get()
            ->map(function($model) {
                return $this->format($model);
            });
    }
}