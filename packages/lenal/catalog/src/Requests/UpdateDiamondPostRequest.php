<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiamondPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug'         => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'shape'        => 'nullable|string|max:255',
            'color'        => 'nullable|string|max:255',
            'cut'          => 'nullable|string|max:255',
            'polish'       => 'nullable|string|max:255',
            'symmetry'     => 'nullable|string|max:255',
            'fluorescence' => 'nullable|string|max:255',
            'clarity'      => 'nullable|string|max:255',
            'culet'        => 'nullable|string|max:255',
            'video'        => 'nullable|string|max:255',
            'raw_price'    => 'nullable|numeric',
            'carat'        => 'nullable|numeric',
            'depth'        => 'nullable|numeric',
            'table'        => 'nullable|numeric',
            'length'       => 'nullable|numeric',
            'width'        => 'nullable|numeric',
            'height'       => 'nullable|numeric',
            'size_ratio'   => 'nullable|numeric',
        ];
    }
}
