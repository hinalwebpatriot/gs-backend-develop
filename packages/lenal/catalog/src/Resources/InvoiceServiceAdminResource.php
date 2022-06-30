<?php

namespace lenal\catalog\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Models\InvoiceService;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class InvoiceServiceResource
 * @mixin InvoiceService
 */
class InvoiceServiceAdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'gst' => $this->gst,
        ];
    }
}