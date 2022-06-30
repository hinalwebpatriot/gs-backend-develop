<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstructorParamsRequest extends FormRequest
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
            'diamond_id' => 'required|integer|exists:diamonds,id',
            'ring_id' => 'required|integer|exists:engagement_rings,id',
            'ring_size_slug' => 'required|exists:ring_sizes,slug'
        ];
    }
}
