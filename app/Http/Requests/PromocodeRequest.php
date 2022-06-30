<?php

namespace App\Http\Requests;

use GSD\Containers\Referral\Interfaces\PromoCodeInterface;
use GSD\Containers\Referral\Services\PromoCodeService;
use Illuminate\Foundation\Http\FormRequest;
use lenal\catalog\Models\Promocode;

class PromocodeRequest extends FormRequest
{
    /**
     * @var Promocode
     */
    private $promocode;

    protected function prepareForValidation()
    {
        if ($this->get('code')) {
            /** @var PromoCodeService $service */
            $service = app(PromoCodeService::class);
            $this->promocode = $service->getPromoCodeInterface($this->get('code'));
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => ['required'],
            'confirm_code' => [$this->has('confirm_code') ? 'required' : 'nullable'],
        ];

        $rules['code'][] = function($attribute, $value, $fails) {
            if (!$this->promocode) {
                $fails(trans('api.promocode-is-not-validate'));
            }
        };

        if ($this->promocode) {
            $rules['code'][] = function($attribute, $value, $fails) {
                if (!$this->promocode->isRelevant()) {
                    $fails(trans('api.promocode-is-not-validate'));
                } elseif ($this->cookie('promocode') == $this->promocode->code) {
                    $fails(trans('api.promocode-already-applied'));
                }
            };

            if ($this->get('confirm_code')) {
                $rules['confirm_code'][] = function($attribute, $value, $fails) {
                    if ($value && !$this->promocode->confirmation($value)) {
                        $fails(trans('api.promocode-confirmation-wrong'));
                    }
                };
            }
        }

        return $rules;
    }

    /**
     * TODO так делать нельзя реквест не должен возвращать модели (наследство от пейко)
     * @return PromoCodeInterface
     */
    public function getPromocode(): PromoCodeInterface
    {
        return $this->promocode;
    }
}
