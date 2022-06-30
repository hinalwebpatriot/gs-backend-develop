<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeddingPutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug'              => 'sometimes|string|max:255',
            'carat_weight'      => 'sometimes|string|max:255',
            'band_width'        => 'sometimes|nullable|numeric',
            'gender'            => 'sometimes|nullable|max:255',
            'ring_style'        => 'sometimes|nullable|string|max:255',
            'ring_collection'   => 'sometimes|nullable|string|max:255',
            'side_stones'       => 'sometimes|nullable|string|max:255',
            'side_setting_type' => 'sometimes|nullable|string|max:255',
            'min_ring_size'     => 'sometimes|nullable|string|max:255',
            'max_ring_size'     => 'sometimes|nullable|string|max:255',
            'metal'             => 'sometimes|nullable|string|max:255',
            'raw_price'         => 'sometimes|nullable|numeric',
            'inc_price'         => 'sometimes|nullable|numeric',

            'image.*' => 'sometimes|nullable|image|dimensions:min_width=400',
            'video'   => 'sometimes|nullable|file',
        ];
    }
}
