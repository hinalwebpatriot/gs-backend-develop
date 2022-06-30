<?php


namespace GSD\Libs\NovaTools\MarginCalc\Http\Controllers;


use App\Http\Controllers\Controller;
use GSD\Libs\NovaTools\MarginCalc\Helpers\MarginCalcHelper;
use Illuminate\Http\Request;
use lenal\PriceCalculate\Facades\CountryVat;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class MarginCalcController
 * @package GSD\Libs\NovaTools\MarginCalc\src\Http\Controllers
 */
class MarginCalcController extends Controller
{
    /**
     * @TODO Большая часть перенесена в ветке с билдер панелью
     */
    public function index()
    {
        $currency = CurrencyRate::getRate('AUD', 'USD');
        $gst = CountryVat::getCountryVat('AU');
        $caratRanges = MarginCalcHelper::getCaratRange();
        $colors = MarginCalcHelper::getColors();
        $clarities = MarginCalcHelper::getClarities();
        $manufacturers = MarginCalcHelper::getManufacturers();
        $soldPercent = config('price_calculate.diamonds_price_update');
        $increasePercent = config('price_calculate.diamonds_price_inc');

        return compact('currency', 'gst', 'caratRanges', 'colors', 'clarities', 'soldPercent', 'increasePercent', 'manufacturers');
    }

    /**
     * @param  Request  $request
     *
     * @return int[]
     */
    public function marginPercent(Request $request)
    {
        $range = explode(' - ', $request->post('range') ??  '0 - 0');
        $clarityId = $request->post('clarityId', 0);
        $colorId = $request->post('colorId', 0);
        $manId = $request->post('manId', 0);
        $isRound = $request->post('isRound', true);

        return [
            'margin' => MarginCalcHelper::getMargin(
                (int) $manId,
                (float) $range[0],
                (float) $range[1],
                (int) $colorId,
                (int) $clarityId,
                (bool) $isRound
            )
        ];
    }
}