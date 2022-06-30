<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstructorUpdateRequest extends FormRequest
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
            'id' => 'required|integer',
            'diamond_id' => 'integer|exists:diamonds,id',
            'ring_id' => 'integer|exists:engagement_rings,id',
            'ring_size_slug' => 'exists:ring_sizes,slug'
        ];
    }
}
