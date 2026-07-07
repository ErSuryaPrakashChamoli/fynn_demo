<?php

// if (! function_exists('indianCurrencyFormat')) {

//     function indianCurrencyFormat($number): string
//     {
//         $number = preg_replace('/[^0-9]/', '', (string) $number);

//         if ($number === '') {
//             return '';
//         }

//         $lastThree = substr($number, -3);
//         $rest = substr($number, 0, -3);

//         if ($rest !== '') {
//             $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

//             return $rest . ',' . $lastThree;
//         }

//         return $lastThree;
//     }
// }

// function indianCurrencyFormat($number): string
// {
//     if ($number === null || $number === '') {
//         return '';
//     }

//     $number = (string) (int) $number;

//     $lastThree = substr($number, -3);
//     $rest = substr($number, 0, -3);

//     if ($rest !== '') {
//         $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
//         return $rest . ',' . $lastThree;
//     }

//     return $lastThree;
// }


// function indianCurrencyFormat($number): string
// {
//     if ($number === null || $number === '') {
//         return '';
//     }

//     // Convert decimal string (e.g. "500000.00") to integer string ("500000")
//     $number = (string) ((int) $number);

//     $lastThree = substr($number, -3);
//     $rest = substr($number, 0, -3);

//     if ($rest !== '') {
//         $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

//         return $rest . ',' . $lastThree;
//     }

//     return $lastThree;
// }


function indianCurrencyFormat($number): string
{
    if ($number === null || $number === '') {
        return '';
    }

    $number = (string) ((int) $number);

    $lastThree = substr($number, -3);
    $rest = substr($number, 0, -3);

    if ($rest !== '') {
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        return $rest . ',' . $lastThree;
    }

    return $lastThree;
}