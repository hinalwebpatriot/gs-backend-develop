<?php

namespace lenal\catalog\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\Products\ProductFieldAssign;

/**
 * @mixin ProductFieldAssign
 */
class CustomFieldResource extends JsonResource
{
    /**
     * @param Collection $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return parent::collection($resource->filter(function(ProductFieldAssign $assign) {
            return $assign->value;
        }));
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'label' => $this->field->label,
          'value' => $this->value,
        ];
    }
}
