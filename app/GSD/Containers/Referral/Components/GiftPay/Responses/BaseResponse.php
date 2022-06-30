<?php

namespace GSD\Containers\Referral\Components\GiftPay\Responses;

use Exception;
use GSD\Containers\Referral\Components\GiftPay\Exceptions\ResponseErrorException;
use Illuminate\Support\Str;

abstract class BaseResponse
{
    /**
     * @throws ResponseErrorException
     */
    public function __construct(string $response)
    {
        $data = json_decode($response);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ResponseErrorException(json_last_error_msg());
        }

        foreach ($data as $key => $value) {
            $field = Str::camel($key);
            if (!property_exists($this, $field)) {
                throw new ResponseErrorException(sprintf('Property "%s" not found', $key));
            }
            $this->$field = $value;
        }
    }
}