<?php


namespace GSD\Containers\Referral\UI\API\Controllers;


use GSD\Containers\Referral\Actions\CheckAction;
use GSD\Containers\Referral\Actions\SendAction;
use GSD\Containers\Referral\DTO\CheckRequestDTO;
use GSD\Containers\Referral\DTO\CheckResponseDTO;
use GSD\Containers\Referral\DTO\SendRequestDTO;
use GSD\Containers\Referral\UI\API\Requests\CheckRequest;
use GSD\Containers\Referral\UI\API\Requests\SendRequest;
use GSD\Containers\Referral\UI\API\Transformers\CheckTransformer;
use GSD\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class ReferralController
 * @package GSD\Containers\Referral\UI\API\Controllers
 */
class ReferralController extends ApiController
{
    /**
     * Проверяет наличие разрешения на отправку промо кодов
     *
     * @param  CheckRequest  $request
     * @param  CheckAction   $action
     *
     * @return JsonResponse
     */
    public function check(CheckRequest $request, CheckAction $action): JsonResponse
    {
        $dto = CheckRequestDTO::fromRequest($request);
        $response = new CheckResponseDTO(['valid' => $action->run($dto->email)]);
        return response()->json(new CheckTransformer($response));
    }

    /**
     * Отправляет промокоды от реферала
     *
     * @param  SendRequest  $request
     * @param  SendAction   $action
     *
     * @return JsonResponse
     */
    public function send(SendRequest $request, SendAction $action): JsonResponse
    {
        $dto = SendRequestDTO::fromRequest($request);
        $result = $action->run($dto);
        return response()->json($result);
    }
}