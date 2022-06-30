<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;

/**
 * Class RingStyleResource
 * @property int $id
 * @property int $diamond_id
 * @property int $ring_id
 * @property mixed $ring_size
 * @property string $engraving
 * @property string $engraving_font
 * @package lenal\catalog\Resources
 */
class CompleteRingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $diamond = Diamond::withCalculatedPrice()->find($this->diamond_id);
        if (!$diamond) {
            logger('CompleteRingResource: no diamond ID=' . $this->diamond_id);
            return [];
        }

        $ring = EngagementRing::withCalculatedPrice()->find($this->ring_id);
        if (!$ring) {
            logger('CompleteRingResource: no ring ID=' . $this->ring_id);
            return [];
        }

        $commonPrice = ceil($diamond->calculated_price + $ring->calculated_price);
        $totalCarat = (string)number_format(floatval($diamond->carat)+floatval($ring->carat_weight), 2, '.','');
        $ring->size_slug = $this->ring_size ?? null;

        return [
            'id' => $this->id,
            'diamond' => new DiamondResource($diamond),
            'ring'    => new EngagementRingResource($ring),
            'common'  => [
                'price'       => $commonPrice,
                'total_carat' => $totalCarat,
                'engraving' => [
                    'text' => $this->engraving,
                    'font' => $this->engraving_font,
                ]
            ]
        ];
    }
}
