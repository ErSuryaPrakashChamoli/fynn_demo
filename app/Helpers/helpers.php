<?php

if (! function_exists('indianCurrencyFormat')) {

    function indianCurrencyFormat($number): string
    {
        $number = preg_replace('/[^0-9]/', '', (string) $number);

        if ($number === '') {
            return '';
        }

        $lastThree = substr($number, -3);
        $rest = substr($number, 0, -3);

        if ($rest !== '') {
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

            return $rest . ',' . $lastThree;
        }

        return $lastThree;
    }
}