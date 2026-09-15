<?php

namespace App\Helpers;

class InvoiceHelper
{
    private static array $dictionary = [
        0                   => 'ZERO',
        1                   => 'ONE',
        2                   => 'TWO',
        3                   => 'THREE',
        4                   => 'FOUR',
        5                   => 'FIVE',
        6                   => 'SIX',
        7                   => 'SEVEN',
        8                   => 'EIGHT',
        9                   => 'NINE',
        10                  => 'TEN',
        11                  => 'ELEVEN',
        12                  => 'TWELVE',
        13                  => 'THIRTEEN',
        14                  => 'FOURTEEN',
        15                  => 'FIFTEEN',
        16                  => 'SIXTEEN',
        17                  => 'SEVENTEEN',
        18                  => 'EIGHTEEN',
        19                  => 'NINETEEN',
        20                  => 'TWENTY',
        30                  => 'THIRTY',
        40                  => 'FORTY',
        50                  => 'FIFTY',
        60                  => 'SIXTY',
        70                  => 'SEVENTY',
        80                  => 'EIGHTY',
        90                  => 'NINETY',
        100                 => 'HUNDRED',
        1000                => 'THOUSAND',
        1000000             => 'MILLION',
        1000000000          => 'BILLION',
    ];

    public static function amountInWords(float|int $amount): string
    {
        $amount = round($amount, 2);
        $parts = explode('.', number_format($amount, 2, '.', ''));
        $ringgit = (int)$parts[0];
        $cents = (int)$parts[1];

        $words = self::integerToWords($ringgit);
        if ($cents > 0) {
            $words .= ' AND CENTS ' . self::integerToWords($cents);
        }

        return trim($words) . ' ONLY';
    }

    public static function integerToWords(int $number): string
    {
        if ($number < 0) {
            return 'NEGATIVE ' . self::integerToWords(abs($number));
        }

        if ($number < 21) {
            return self::$dictionary[$number] ?? '';
        }

        if ($number < 100) {
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $res = self::$dictionary[$tens] ?? '';
            if ($units) {
                $res .= ' ' . (self::$dictionary[$units] ?? '');
            }
            return $res;
        }

        if ($number < 1000) {
            $hundreds = (int)($number / 100);
            $remainder = $number % 100;
            $res = (self::$dictionary[$hundreds] ?? '') . ' HUNDRED';
            if ($remainder) {
                $res .= ' ' . self::integerToWords($remainder);
            }
            return $res;
        }

        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int)($number / $baseUnit);
        $remainder = $number % $baseUnit;

        $res = self::integerToWords($numBaseUnits) . ' ' . (self::$dictionary[(int)$baseUnit] ?? '');
        if ($remainder) {
            $res .= ' ' . self::integerToWords($remainder);
        }

        return $res;
    }
}
