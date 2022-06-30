<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 1/29/19
 * Time: 12:10 PM
 */

namespace lenal\AppSettings\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'can_ship' => (boolean)$this->shipment,
            'vat_percent' => $this->vat
        ];
    }
}