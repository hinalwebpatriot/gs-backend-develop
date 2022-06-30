<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \lenal\catalog\Models\Order
 */
class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        if (!$this->id) {
            return [];
        }
        $shared = [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'additional_phone_number' => $this->additional_phone_number,
            'comment' => $this->comment,
            'gift' => $this->gift,
        ];
        $office = [
            'address' => $this->address,
            'company_name' => $this->company_name,
            'town_city' => $this->town_city,
            'zip_postal_code' => $this->zip_postal_code,
            'country' => $this->country,
            'state' => $this->state,
            'billing_address' => $this->billing_address,
            'special_package' => $this->special_package,
        ];
        $home = [
            'first_name' => $this->first_name_home,
            'last_name' => $this->last_name_home,
            'phone_number' => $this->phone_number_home,
            'additional_phone_number' => $this->add_phone_number_home,
            'address' => $this->address_home,
            'company_name' => $this->company_name,
            'town_city' => $this->town_city_home,
            'zip_postal_code' => $this->zip_postal_code_home,
            'country' => $this->country_home,
            'state' => $this->state_home,
            'appartman_number' => $this->appartman_number_home,
        ];

        $showroom = [
            'id_showroom' => $this->id_showroom,
        ];

        $order['Shared'] = $shared;
        if ($this->id_showroom) {
            $order['Showroom'] = $showroom;
        } else {
            $order['Office'] = $office;
            if ($this->billing_address == 0) {
                $order['Home'] = $home;
            }
        }

        $order_colection = $this->cartItems->map(function ($cartItem) {
            return $cartItem->product_type::where('id', $cartItem->product_id)
                ->get()->each(function ($item) use ($cartItem) {
                    $item->сalculated_price = $cartItem->price;
                    $item->currency = $this->currency;
                    $item->size_slug = $cartItem->size_slug;
                })
                ->first();
        });

        $pdf_invoice = $this->getMedia('invoices');

        return [
            'products_list' => OrderItemResource::collection($order_colection),
            'products_count' => $order_colection->count(),
            'order_info' => $order,
            'products_total' => [
                'count' => $this->total_price,
                'currency' => $this->currency
            ],
            'payment_system' => new PaysystemResource($this->paysystem),
            'is_payed' => (boolean)$this->is_payed,
            'order_type' => $this->orderType(),
            'invoice' => !empty($pdf_invoice[0]) ? $pdf_invoice[0]->getFullUrl() : null,
            'service_invoice' => $this->invoice ? new InvoiceResource($this->invoice) : null,
        ];
    }
}