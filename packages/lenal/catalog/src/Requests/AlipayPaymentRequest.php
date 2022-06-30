<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlipayPaymentRequest extends FormRequest
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
            /*'notice_id' => 'required',
            'merchant_trade_no' => 'required|string',
            'trade_amount' => 'required|string',*/
        ];
    }
}
