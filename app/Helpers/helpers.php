<?php

if (!function_exists('generateLuhnNumber')) {
    function generateLuhnNumber($length = 12)
    {
        $base = '';
        for ($i = 0; $i < $length - 1; $i++) {
            $base .= rand(0, 9);
        }
        return $base . calculateLuhnChecksum($base);
    }
}

if (!function_exists('calculateLuhnChecksum')) {
    function calculateLuhnChecksum($number)
    {
        $digits = str_split($number);
        $sum = 0;
        $alt = true;

        for ($i = count($digits) - 1; $i >= 0; $i--) {
            $digit = (int) $digits[$i];
            if ($alt) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
            $alt = !$alt;
        }

        return (10 - ($sum % 10)) % 10;
    }
}

if (!function_exists('validateLuhnNumber')) {
    function validateLuhnNumber($number)
    {
        return calculateLuhnChecksum(substr($number, 0, -1)) == substr($number, -1);
    }
}