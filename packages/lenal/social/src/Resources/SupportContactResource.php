<?php
namespace lenal\social\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SupportContactResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service' => $this->service,
            'contacts' => Arr::flatten($this->value),
        ];
    }
}