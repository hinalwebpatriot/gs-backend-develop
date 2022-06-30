<?php


namespace GSD\Containers\GoogleMerchant\Data\Repositories;


use GSD\Ship\Parents\Repositories\Repository;
use lenal\catalog\Models\Products\Product as Model;

class ProductsRepository extends Repository
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
}