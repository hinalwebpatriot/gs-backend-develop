<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EngagementFeedFiltersRequest extends FormRequest
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
            'page'              => 'nullable|integer',
            'per_page'          => 'nullable|integer',
            'price'             => 'required|array',
            'price.min'         => 'required|integer',
            'price.max'         => 'required|integer',
            'gender'            => 'nullable|string',
            'center_stone_size' => 'nullable|integer',
            'sort'              => 'nullable|array',
            'sort.field'        => 'nullable|string',
            'sort.order'        => 'nullable|string',
            'ring_size'         => 'nullable|array',
            'ring_size.*'       => 'string',
            'shape'             => 'nullable|array',
            'shape.*'           => 'string',
            'metal'             => 'nullable|array',
            'metal.*'           => 'string',
            'style'             => 'nullable|array',
            'style.*'           => 'string',
            'collection'        => 'nullable|array',
            'collection.*'      => 'string',
            'offers'            => 'nullable|array',
            'offers.*'          => 'string',
        ];
    }
}
