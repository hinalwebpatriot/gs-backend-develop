<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProceedPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => 'required|integer|exists:order,id',
            'paysystem_id' => 'required|integer|exists:paysystems,id',
        ];
    }
}
