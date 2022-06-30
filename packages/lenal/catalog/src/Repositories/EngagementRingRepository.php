<?php

namespace lenal\catalog\Repositories;

use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use lenal\catalog\DTO\EngagementRingsFilterDTO;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\RingCollection;

/**
 * @package \lenal\catalog\Repositories
 * @class   \lenal\catalog\Repositories\EngagementRingRepository
 */
class EngagementRingRepository extends Repository
{
    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return EngagementRing::class;
    }

    public function list(EngagementRingsFilterDTO $filters)
    {

        $sub = $this->getQuery($filters);
        switch ($filters->sortField) {
            case 'new':
                $sub = $sub->orderBy('custom_sort')->orderBy('raw_price');
                break;
            case 'price':
                $sub = $sub->orderBy('raw_price', $filters->sortOrder);
                break;
            default:
                $sub = $sub->orderBy('raw_price');
        }
        $join = $this->startConditions()->fromSub($sub->getQuery(), 'sub_table')->groupBy('group_sku');

        $resultQuery = $this->startConditions()
            ->rightJoinSub($join, 'ef', 'ef.id', '=', 'engagement_rings.id')
            ->with(['stoneShape', 'ringCollection', 'ringStyle', 'metal', 'offers', 'firstMedia'])
            ->select('*')
            ->withCalcPrice();
        $result = $resultQuery->paginate($filters->perPage, ['*'], $filters->page);

        $collect = collect($result->items());
        $ids = $collect->pluck('id')->toArray();
        $groups = $collect->pluck('group_sku')->toArray();
        $groupItems = $this->getQuery($filters)
            ->select([
                'id',
                'group_sku',
                'metal_id'
            ])
            ->whereIn('group_sku', $groups)
            ->whereNotIn('id', $ids)
            ->toBase()
            ->get()
            ->groupBy('group_sku', true);
        $result->through(function (EngagementRing $item) use ($groupItems) {
            $items = collect();
            $items['mainItem'] = $item;
            $items['extraItems'] = collect();
            $groupItems->get($item->group_sku, collect())->each(function ($item) use (&$items) {
                unset($item->group_sku);
                $items['extraItems'][] = $item;
            });
            return [$item->group_sku => $items];
        });

        return $result;
    }

    /**
     * @param  EngagementRingsFilterDTO  $filters
     * @return Builder
     */
    protected function getQuery(EngagementRingsFilterDTO $filters): Builder
    {
        $priceField = EngagementRing::getCalculatedPriceField();
        $query = $this->startConditions()->selectRaw('DISTINCT('.$priceField.'), id, group_sku')
            ->whereBetween(DB::raw($priceField), [$filters->priceMin, $filters->priceMax])
            ->where('is_active', 1);
        if ($filters->metal) {
            $query = $query->whereHas('metal', function (Builder $query) use ($filters) {
                $query->whereIn('slug', $filters->metal);
            });
        }
        if ($filters->shape) {
            $query = $query->whereHas('stoneShape', function (Builder $query) use ($filters) {
                $query->whereIn('slug', $filters->shape);
            });
        }
        if ($filters->style) {
            $query = $query->whereHas('ringStyle', function (Builder $query) use ($filters) {
                $query->whereIn('slug', $filters->style);
            });
        }
        if ($filters->collection) {
            $query = $query->whereHas('ringCollection', function (Builder $query) use ($filters) {
                $query->whereIn('slug', $filters->collection);
            });
        }
        if ($filters->gender) {
            $availableGender = ['female' => 'f', 'male' => 'm'];
            $gender = array_merge(['n'], [$availableGender[$filters->gender] ?? 'n']);
            $query = $query->whereIn('gender', $gender);
        }
        if ($filters->centerStoneSize > 0) {
            $query = $query->where('min_stone_carat', '<=', $filters->centerStoneSize);
            $query = $query->where('max_stone_carat', '>=', $filters->centerStoneSize);
        }
        if ($filters->offers) {
            $query = $query->whereHas('offers', function (Builder $query) use ($filters) {
                $query->whereIn('slug', $filters->offers);
            });
        }
        return $query->orderBy('is_top', 'desc');
    }
}
