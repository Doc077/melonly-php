<?php

namespace Melonly\Support\Helpers;

class Regex
{
    public static function replace(string $pattern, string $replace, string $string): string|array|null
    {
        return preg_replace($pattern, $replace, $string);
    }
}
