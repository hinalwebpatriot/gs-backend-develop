<?php

namespace lenal\offers\Rules;

use Illuminate\Contracts\Validation\Rule;
use lenal\offers\Models\Offer;

class OneSaleOffer implements Rule
{
    private $except_id;

    public function __construct($except_id)
    {
        $this->except_id = $except_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $offer_builder = Offer::where($attribute, 1);

        if (!is_null($this->except_id)) {
            $offer_builder->where('id', '!=', $this->except_id);
        }

        return (bool)$value === false
            || $offer_builder->get()->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only one offer with sale is allowed.';
    }
}
