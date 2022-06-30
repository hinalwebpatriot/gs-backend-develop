<?php

namespace lenal\promo_registration\Requests;

use Illuminate\Foundation\Http\FormRequest;
use lenal\promo_registration\Services\PromoRegistrationService;

class PromoRegisterRequest extends FormRequest
{
    public $step;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:promo_registrations'],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => trans('api.first-name'),
            'last_name' => trans('api.last-name'),
            'email' => trans('api.email'),
        ];
    }
}
