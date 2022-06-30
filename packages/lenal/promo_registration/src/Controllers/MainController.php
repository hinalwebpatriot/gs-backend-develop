<?php

namespace lenal\promo_registration\Controllers;

use App\Http\Controllers\Controller;
use lenal\promo_registration\Repositories\PromoRegistrationRepository;
use lenal\promo_registration\Services\PromoRegistrationService;
use lenal\promo_registration\Requests\PromoRegisterRequest;
use lenal\promo_registration\Resources\ContentResource;


class MainController extends Controller
{
    /**
     * @var PromoRegistrationRepository
     */
    private $promoRepository;

    public function __construct(PromoRegistrationRepository $promoRepository)
    {
        $this->promoRepository = $promoRepository;
    }

    public function content()
    {
        return new ContentResource($this->promoRepository->firstActive());
    }

    public function register(PromoRegisterRequest $request)
    {
        (new PromoRegistrationService($request->all()))->register();

        return response()->json([
            'step' => 'registered',
            'message' => trans('api.email-successfully-added'),
        ]);
    }
}
