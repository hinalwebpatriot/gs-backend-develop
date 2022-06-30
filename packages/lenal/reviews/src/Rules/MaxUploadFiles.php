<?php

namespace lenal\reviews\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxUploadFiles implements Rule
{
    const MAX_COUNT = 4;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return count($value) <= self::MAX_COUNT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans_choice('api.reviews.error.max_photos_upload',  self::MAX_COUNT);
    }
}
