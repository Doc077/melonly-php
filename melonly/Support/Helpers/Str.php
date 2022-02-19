<?php

namespace Melonly\Support\Helpers;

class Str
{
    public function __construct(protected string $content)
    {
    }

    public static function uppercase(string $string): string
    {
        return strtoupper($string);
    }

    public static function lowercase(string $string): string
    {
        return strtolower($string);
    }

    public static function uppercaseFirst(string $string): string
    {
        return ucfirst($string);
    }

    public static function lowercaseFirst(string $string): string
    {
        return lcfirst($string);
    }

    public static function substring(string $string, int $offset, ?int $length = null): string
    {
        if ($length === null) {
            substr($string, $offset);
        }

        return substr($string, $offset, $length);
    }

    public static function pascalCase(string $string, bool $replaceDashes = true): string
    {
        if ($replaceDashes) {
            $string = self::replace('-', ' ', $string);
            $string = self::replace('_', ' ', $string);
        }

        $string = ucwords(strtolower($string));

        return self::replace(' ', '', $string);
    }

    public static function kebabCase(string $string): string
    {
        $string = Regex::replace('/\s+/u', '', ucwords($string));
        $string = Regex::replace('/(.)(?=[A-Z])/u', '$1-', $string);

        return self::lowercase($string);
    }

    public static function contains(string $search, string $string): bool
    {
        return str_contains($search, $string);
    }

    public static function startsWith(string $search, string $string): bool
    {
        return str_starts_with($search, $string);
    }

    public static function endsWith(string $search, string $string): bool
    {
        return str_ends_with($search, $string);
    }

    public static function replace(string $from, string $to, string $string): string
    {
        return str_replace($from, $to, $string);
    }

    public static function length(string $string): int
    {
        return strlen($string);
    }

    public static function random(int $length = 32): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $bytes = random_bytes($length - $len);

            $string .= substr(base64_encode($bytes), 0, $length - $len);
        }

        return $string;
    }

    public static function split(string $needle, string $string): array
    {
        return explode($needle, $string);
    }

    public static function splitAtOccurence(string $needle, int $occurence, string $string): array
    {
        $max = strlen($string);
        $n = 0;

        for ($i = 0; $i < $max; $i++) {
            if ($string[$i] === $needle) {
                $n++;

                if ($n >= $occurence) {
                    break;
                }
            }
        }

        $array[] = substr($string, 0, $i);
        $array[] = substr($string, $i + 1, $max);

        return $array;
    }
}
