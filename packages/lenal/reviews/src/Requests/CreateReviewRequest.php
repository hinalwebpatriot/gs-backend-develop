<?php

namespace lenal\reviews\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\reviews\Rules\MaxUploadFiles;

class CreateReviewRequest extends FormRequest
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
            'title' => 'required',
            'author_name' => 'required',
            'author_email' => 'email',
            'text' => 'required',
            'rate' => 'required|integer|between:1,5',
            'product_id' => 'numeric|required_with:product_type',
            'product_type' => 'required_with:product_id|not_in:uknown',
            'photos' => [new MaxUploadFiles]
        ];
    }

    public function getValidatorInstance()
    {
        if ($this->request->has('product_type')) {
            $this->setProductClass();
        }
        return parent::getValidatorInstance();
    }

    protected function setProductClass()
    {
        $requestType = $this->request->get('product_type');
        switch ($requestType) {
            case 'diamonds': $productClass = Diamond::class; break;
            case 'engagement-rings': $productClass = EngagementRing::class; break;
            case 'wedding-rings': $productClass = WeddingRing::class; break;
            case 'products': $productClass = Product::class; break;
            default:$productClass = 'uknown'; break;
        }
        $this->merge(['product_type' => $productClass]);
    }

}
