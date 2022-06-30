<?php

namespace lenal\catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiamondPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku'          => 'required|string|max:255|unique:diamonds',
            'slug'         => 'required|string|max:255|unique:diamonds',
            'manufacturer' => 'required|string|max:255',
            'shape'        => 'required|string|max:255',
            'color'        => 'nullable|string|max:255',
            'cut'          => 'nullable|string|max:255',
            'polish'       => 'nullable|string|max:255',
            'symmetry'     => 'nullable|string|max:255',
            'fluorescence' => 'nullable|string|max:255',
            'clarity'      => 'nullable|string|max:255',
            'culet'        => 'nullable|string|max:255',
            'certificate'  => 'nullable|string|max:255',
            'raw_price'    => 'required|numeric',
            'carat'        => 'required|numeric',
            'depth'        => 'nullable|numeric',
            'table'        => 'nullable|numeric',
            'length'       => 'nullable|numeric',
            'width'        => 'nullable|numeric',
            'height'       => 'nullable|numeric',
            'size_ratio'   => 'nullable|numeric',
            'video'        => 'nullable|string|max:255',
        ];
    }
}
