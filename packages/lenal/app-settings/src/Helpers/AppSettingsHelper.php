<?php

namespace lenal\AppSettings\Helpers;

use lenal\AppSettings\Models\Location;
use lenal\AppSettings\Resources\CurrencyResource;
use lenal\AppSettings\Resources\LocationResource;
use lenal\PriceCalculate\Models\CurrencyRate;
use Illuminate\Support\Facades\Cookie;

class AppSettingsHelper
{
    public function locales()
    {
        $locales = array_keys(config('translatable.locales'));
        foreach ($locales as $locale) {
            $result[] = [
                'code' => $locale,
                'name' => trans("api.locales.$locale")
            ];
        }
        return response(['data' => $result]);
    }

    public function currencies()
    {
        return CurrencyResource::collection(CurrencyRate::all());
    }

    public function locations()
    {
        return LocationResource::collection(Location::where('shipment', '1')->get()->sortBy('name'));
    }

    public function locationsData()
    {
        $locales = array_keys(config('translatable.locales'));
        foreach ($locales as $locale) {
            $result[] = [
                'code' => $locale,
                'name' => trans("api.locales.$locale")
            ];
        }
        $data["lang"]  = $result;
        $cur_all = CurrencyRate::all();

        foreach ($cur_all as $k=>$cur) {
            $currencys[$cur->from_currency]["code"] = $cur->from_currency;
            $currencys[$cur->from_currency]["name"] = $cur->from_currency;
        }
        $data["currency"]  = array_values($currencys);
        $data["location"]  = LocationResource::collection(Location::where('shipment', '1')->get()->sortBy('name'));
        $data["selected"]  = $this->getSavedItemsFromCookie('locations_selected') ?
            $this->getSavedItemsFromCookie('locations_selected') : null;

        return $data;
    }

    public function getSavedItemsFromCookie($cookieKey)
    {
        return json_decode(Cookie::get($cookieKey), true) ? : [];
    }

    public function locationsSelected($request)
    {
        return response(['message' => trans('api.locations.selected')])->withCookie(
            cookie()->forever('locations_selected', json_encode($request->toArray())));
    }

}
