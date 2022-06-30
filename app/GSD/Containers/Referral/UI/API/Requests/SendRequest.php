<?php


namespace GSD\Containers\Referral\UI\API\Requests;


use GSD\Ship\Parents\Requests\Request;

/**
 * Class SendRequest
 * @package GSD\Containers\Referral\UI\API\Requests
 *
 * @property string $sender
 * @property string $sender_first_name
 * @property string $sender_last_name
 * @property array  $recipients
 * @property string $comment
 * @property bool   $subscribe
 */
class SendRequest extends Request
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
            'sender_first_name'       => 'required|string',
            'sender_last_name'        => 'required|string',
            'sender'                  => 'required|string|email',
            'recipients'              => 'required',
            'recipients.*.email'      => 'required|string|email',
            'recipients.*.first_name' => 'required|string',
            'recipients.*.last_name'  => 'nullable|string',
            'comment'                 => 'nullable|string',
            'subscribe'               => 'boolean',
        ];
    }
}