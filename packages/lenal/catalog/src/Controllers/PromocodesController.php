<?php

namespace lenal\catalog\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\PromocodeRequest;
use Illuminate\Http\Response;
use lenal\catalog\Services\Promocode\PromocodeService;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PromocodesController extends Controller
{
    public function apply(PromocodeRequest $request, PromocodeService $promocodeService)
    {
        try {
            $res = $promocodeService->create($request->getPromocode(), $request->get('confirm_code'));

            if ($res['with_confirmation'] == 0) {
                return response()->json([
                    'success' => true,
                    'data'    => $res
                ])->withCookie(cookie()->forever('promocode', $request->getPromocode()->getCode()));
            } else {
                return response()->json(['success' => true, 'data' => $res]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @return Response
     */
    public function remove(): Response
    {
        $cookieCode = request()->cookie('promocode');
        if (!$cookieCode) {
            throw new UnprocessableEntityHttpException('Promo-code not found');
        }
        return response()->noContent()->withCookie(cookie()->forget('promocode'));
    }
}
