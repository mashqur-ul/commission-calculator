<?php

if (!function_exists("ceil_up")) {
    function ceil_up ($number, $precision = 2, $seperator = '.') {

        if (is_numeric($number) && is_int($precision) && ($precision > 0)) {

            $explodedNumber = explode($seperator, $number);
            $integer = $explodedNumber[0];
            $fraction = $explodedNumber[1];

            $significantDigit = (int) substr($fraction, $precision, 1);
            $fractionToPrecision = (int) substr($fraction, 0, $precision);
            $ceilUpFraction = $significantDigit > 0 ? ++$fractionToPrecision : $fractionToPrecision;

            return $integer.$seperator.$ceilUpFraction;
        }

        return $number;
    }
}