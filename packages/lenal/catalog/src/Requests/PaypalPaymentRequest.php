<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaypalPaymentRequest extends FormRequest
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
            'token' => 'required',
            'paymentId' => 'required_with:PayerID',
            'PayerID' => 'required_with:paymentId',
        ];
    }
}
