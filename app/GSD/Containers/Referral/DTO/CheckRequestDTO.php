<?php


namespace GSD\Containers\Referral\DTO;


use GSD\Containers\Referral\UI\API\Requests\CheckRequest;
use GSD\Ship\Parents\DTO\DTO;

/**
 * Class CheckRequestDTO
 * @package GSD\Containers\Referral\DTO
 */
class CheckRequestDTO extends DTO
{
    public string $email;

    public static function fromRequest(CheckRequest $request): CheckRequestDTO
    {
        return new static([
            'email' => $request->email
        ]);
    }
}