<?php

namespace lenal\catalog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class EngagementsHelper
 *
 * @package lenal\catalog\Facades
 * @method static getEngagementRings():
 *         \lenal\catalog\Resources\EngagementRingGroupCollection
 * @method static getEngagementRing(int $id): \lenal\catalog\Models\Diamonds\EngagementRing
 * @method static createEngagement(\Illuminate\Http\Request $request): \Illuminate\Http\Resources\Json\JsonResource
 * @method static updateEngagement(string $sku, \Illuminate\Http\Request $request):
 *         \lenal\catalog\Models\Diamonds\EngagementRing
 * @method static getFilters(): array
 * @mixin \lenal\catalog\Helpers\EngagementsHelper
 */
class EngagementsHelper extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'engagements_helper';
    }
}
