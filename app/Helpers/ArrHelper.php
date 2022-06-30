<?php

namespace App\Helpers;


use Illuminate\Support\Arr;

class ArrHelper
{
    public static function exist($arr, $searchItem)
    {
        return !!Arr::first($arr, function($item) use ($searchItem) {
            return count(array_diff(Arr::only($item, array_keys($searchItem)), $searchItem)) == 0;
        });
    }
}