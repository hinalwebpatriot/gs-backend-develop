<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'Shared.email'                     => 'required|string|max:255',
                'Shared.first_name'                => 'required|string|max:255',
                'Shared.last_name'                 => 'required|string|max:255',
                'Shared.phone_number'              => 'required|string|max:255',
                'Shared.additional_phone_number'   => 'nullable|string|max:255',
                'Shared.gift'                      => 'nullable|boolean',
                'Shared.comment'                   => 'nullable|string|max:255',
                'Office.address'                   => 'nullable|string|max:255',
                'Office.company_name'              => 'nullable|string|max:255',
                'Office.town_city'                 => 'nullable|string|max:255',
                'Office.zip_postal_code'           => 'nullable|string|max:255',
                'Office.country'                   => 'nullable|string|max:255',
                'Office.state'                     => 'nullable|string|max:255',
                'Office.billing_address'           => 'nullable|boolean',
                'Office.special_package'           => 'nullable|boolean',
                'Home.first_name'                  => 'nullable|string|max:255',
                'Home.last_name'                   => 'nullable|string|max:255',
                'Home.phone_number'                => 'nullable|string|max:255',
                'Home.additional_phone_number'     => 'nullable|string|max:255',
                'Home.address'                     => 'nullable|string|max:255',
                'Home.town_city'                   => 'nullable|string|max:255',
                'Home.zip_postal_code'             => 'nullable|string|max:255',
                'Home.country'                     => 'nullable|string|max:255',
                'Home.state'                       => 'nullable|string|max:255',
                'Home.appartman_number'            => 'nullable|string|max:255',
                'Showroom.id_showroom'             => 'nullable|integer',

        ];
    }
}
