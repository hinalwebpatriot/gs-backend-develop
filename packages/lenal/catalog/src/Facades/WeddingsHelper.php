<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;
use lenal\catalog\Models\Rings\WeddingRing;

/**
 * Class EngagementsHelper
 *
 * @package lenal\catalog\Facades
 * @method static createWedding(\Illuminate\Http\Request $request): \Illuminate\Http\Resources\Json\JsonResource
 * @method static updateWedding(string $sku, \Illuminate\Http\Request $request):
 *         \Illuminate\Http\Resources\Json\JsonResource
 * @method static getFilters(): array
 * @method static getWeddingRings(\Illuminate\Http\Request $request):
 *         \Illuminate\Contracts\Pagination\LengthAwarePaginator
 * @method static getWeddingRing(int $id): \lenal\catalog\Models\Rings\WeddingRing
 * @method static importImages(WeddingRing $wedding, string $folder, string $filename): void
 * @mixin \lenal\catalog\Helpers\WeddingsHelper
 */
class WeddingsHelper extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'weddings_helper';
    }
}
