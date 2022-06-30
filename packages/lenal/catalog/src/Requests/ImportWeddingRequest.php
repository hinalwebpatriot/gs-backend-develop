<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportWeddingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku'           => 'required|string|max:255',
            'slug'          => 'required|string|max:255',
            'carat_weight'  => 'required|string|max:255',
            'raw_price'     => 'required|numeric',
            'inc_price'     => 'numeric',
            'band_width'    => 'nullable|numeric',
            'gender'        => 'nullable|string|in:male,female',

            'ring_collection'   => 'nullable|string|max:255',
            'ring_style'        => 'nullable|string|max:255',
            'side_setting_type' => 'nullable|string|max:40',
            'min_ring_size'     => 'required|string|max:255',
            'max_ring_size'     => 'required|string|max:255',
            'metal'             => 'required|string|max:255',

            'image.*' => 'nullable|image|dimensions:min_width=400',
            'video'   => 'nullable|file',
        ];
    }
}
