<?php

namespace Melonly\Support\Helpers;

class Math
{
    public const PI = M_PI;

    public const E = M_E;

    public const EULER = M_EULER;

    public static function absolute(int|float $number): int|float
    {
        return abs($number);
    }

    public static function binToDec(string $number): int|float
    {
        return bindec($number);
    }

    public static function binToHex(string $number): string
    {
        return bin2hex($number);
    }

    public static function ceil(int|float $number, int $precision): int|float
    {
        return round($number, $precision, PHP_ROUND_HALF_UP);
    }

    public static function cos(float $number): float
    {
        return cos($number);
    }

    public static function decToBin(int $number): string
    {
        return decbin($number);
    }

    public static function decToHex(int $number): string
    {
        return dechex($number);
    }

    public static function decToOct(int $number): string
    {
        return decoct($number);
    }

    public static function floor(int|float $number, int $precision): int|float
    {
        return round($number, $precision, PHP_ROUND_HALF_DOWN);
    }

    public static function hexToDec(string $number): int|float
    {
        return hexdec($number);
    }

    public static function log(int|float $number, float $base = self::E): int|float
    {
        return log($number, $base);
    }

    public static function max(float $number, ...$values): float
    {
        return max($number, $values);
    }

    public static function min(float $number, ...$values): float
    {
        return min($number, $values);
    }

    public static function logBase10(int|float $number): int|float
    {
        return log10($number);
    }

    public static function octToDec(string $number): int|float
    {
        return octdec($number);
    }

    public static function pow(int|float $number, mixed $exponent): int|float
    {
        return $number ** $exponent;
    }

    public static function sin(float $number): float
    {
        return sin($number);
    }

    public static function squareRoot(int|float $number): int|float
    {
        return sqrt($number);
    }

    public static function tan(float $number): float
    {
        return tan($number);
    }
}
