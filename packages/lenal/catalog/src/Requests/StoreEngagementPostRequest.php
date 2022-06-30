<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEngagementPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku'        => 'required|string|max:255|unique:engagement_rings',
            'slug'       => 'required|string|max:255|unique:engagement_rings',
            'raw_price'  => 'required|numeric',
            'inc_price'  => 'numeric',
            'band_width' => 'nullable|numeric',

            'ring_collection'   => 'required|string|max:255',
            'ring_style'        => 'nullable|string|max:255',
            'stone_shape'       => 'required|string|max:255',
            'stone_size'        => 'required|numeric',
            'setting_type'      => 'required|string|max:40',
            'side_setting_type' => 'nullable|string|max:40',
            'min_ring_size'     => 'required|string|max:255',
            'max_ring_size'     => 'required|string|max:255',
            'metal'             => 'required|string|max:255',

            'image.*' => 'sometimes|nullable|image|dimensions:min_width=400',
            'video'   => 'sometimes|nullable|file',
        ];
    }
}
