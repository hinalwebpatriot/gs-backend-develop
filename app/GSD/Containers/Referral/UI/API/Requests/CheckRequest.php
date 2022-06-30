<?php


namespace GSD\Containers\Referral\UI\API\Requests;


use GSD\Ship\Parents\Requests\Request;

/**
 * Class CheckRequest
 * @package GSD\Containers\Referral\UI\API\Requests
 *
 * @property string $email
 */
class CheckRequest extends Request
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
            'email' => 'required|string|email',
        ];
    }

}