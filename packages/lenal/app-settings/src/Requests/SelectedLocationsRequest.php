<?php

namespace lenal\AppSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectedLocationsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency.code'         => 'required|string|max:255',
            'currency.name'         => 'required|string|max:255',
            'location.code'         => 'required|string|max:255',
            'location.name'         => 'required|string|max:255',
            'location.can_ship'     => 'required|boolean',
            'location.vat_percent'  => 'required',
        ];
    }
}
