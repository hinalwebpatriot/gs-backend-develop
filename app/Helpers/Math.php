<?php

namespace App\Helpers;


class Math
{
    public static function addPercent($value, $percent)
    {
        return $value * (($percent/100) + 1);
    }

    public static function minusPercent($value, $percent)
    {
        return $value * ((1 - $percent/100));
    }

    public static function percent($value, $percent)
    {
        return $value * $percent/100;
    }

    public static function ceilUp($amount, $precision = 2)
    {
        $precisionValue = pow(10, $precision);

        return ceil($amount * $precisionValue) / $precisionValue;
    }
}