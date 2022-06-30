<?php

namespace lenal\AppSettings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'code' => $this->from_currency,
            'name' => $this->from_currency
        ];
    }
}