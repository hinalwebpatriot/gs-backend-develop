<?php

namespace lenal\catalog\DTO;

use GSD\Ship\Parents\DTO\DTO;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EngagementRingsFilterDTO extends DTO
{
    public int    $priceMin;
    public int    $priceMax;
    public array  $ringSize;
    public array  $shape;
    public array  $metal;
    public array  $style;
    public array  $collection;
    public array  $offers;
    public string $gender;
    public float  $centerStoneSize;
    public string $sortField;
    public string $sortOrder;

    public int $page;
    public int $perPage;

    public static function loadFromRequest(Request $request)
    {
        $all = $request->all();
        $dto['page'] = (int) Arr::get($all, 'page', 1);
        $dto['perPage'] = (int) Arr::get($all, 'per_page', 20);
        $dto['priceMin'] = (int) Arr::get($all, 'price.min', 0);
        $dto['priceMax'] = (int) Arr::get($all, 'price.max', 0);
        $dto['sortField'] = Arr::get($all, 'sort.field') ?? 'new';
        $dto['sortOrder'] = Arr::get($all, 'sort.order') ?? 'asc';
        $dto['ringSize'] = collect(Arr::get($all, 'ring_size', []))->map(function ($val) {
            return (double) $val;
        })->toArray();
        $dto['shape'] = Arr::get($all, 'shape') ?? [];
        $dto['metal'] = Arr::get($all, 'metal') ?? [];
        $dto['style'] = Arr::get($all, 'style') ?? [];
        $dto['collection'] = Arr::get($all, 'collection') ?? [];
        $dto['offers'] = Arr::get($all, 'offers') ?? [];
        $dto['gender'] = Arr::get($all, 'gender', 'female');
        $dto['centerStoneSize'] = (double) Arr::get($all, 'center_stone_size', 0);
        return new static($dto);
    }
}
