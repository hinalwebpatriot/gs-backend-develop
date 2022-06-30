<?php


namespace GSD\Containers\Referral\UI\API\Transformers;


use GSD\Containers\Referral\DTO\CheckResponseDTO;
use GSD\Ship\Parents\Transformers\Transformer;

/**
 * Class CheckTransformer
 * @package GSD\Containers\Referral\UI\API\Transformers
 *
 * @mixin CheckResponseDTO
 */
class CheckTransformer extends Transformer
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'is_valid' => $this->valid
        ];
    }
}