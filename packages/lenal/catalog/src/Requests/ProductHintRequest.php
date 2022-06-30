<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductHintRequest extends FormRequest
{
    public $linkRegExp = '/.*\/(diamond|engagement-rings|wedding-rings)\/.+-(\d+).*/';
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
            'recipient_name' => 'required',
            'text' => 'required',
            'sender_name' => 'required',
            'recipient_email' => 'required|email',
            'link' => 'required',
            'type' => 'required',
            'id' => 'required',
        ];
    }


}
