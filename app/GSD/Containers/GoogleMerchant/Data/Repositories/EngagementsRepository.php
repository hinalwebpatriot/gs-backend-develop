<?php


namespace GSD\Containers\GoogleMerchant\Data\Repositories;


use GSD\Ship\Parents\Repositories\Repository;
use lenal\catalog\Models\Rings\EngagementRing as Model;

class EngagementsRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * @return Model[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getList()
    {
        return $this->startConditions()->withCalculatedPrice()->where('is_active', true)->get();
    }

    /**
     * @return Model[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getNewRenderList()
    {
        return $this->startConditions()
            ->withCalculatedPrice()
            ->where('is_active', true)
            ->where('has_new_renders', true)
            ->get();
    }

    /**
     * @return Model[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getOldRenderList()
    {
        return $this->startConditions()
            ->withCalculatedPrice()
            ->where('is_active', true)
            ->where('has_new_renders', false)
            ->get();
    }
}