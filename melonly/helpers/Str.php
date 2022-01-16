<?php

namespace Melonly\Support\Helpers;

class Str {
    public static function uppercase(string $string): string {
        return strtoupper($string);
    }

    public static function lowercase(string $string): string {
        return strtolower($string);
    }

    public static function substring(string $string, int $offset): string {
        return substr($string, $offset);
    }

    public static function pascalCase(string $string, bool $replaceDashes = true): string {
        if ($replaceDashes) {
            $string = str_replace('-', ' ', $string);
            $string = str_replace('_', ' ', $string);
        }

        $string = ucwords(strtolower($string));

        return str_replace(' ', '', $string);
    }

    public static function contains(string $search, string $string): bool {
        return (bool) str_contains($search, $string);
    }

    public static function startsWith(string $search, string $string): bool {
        return (bool) str_starts_with($search, $string);
    }

    public static function endsWith(string $search, string $string): bool {
        return (bool) str_ends_with($search, $string);
    }

    public static function replace(string $from, string $to, string $string): string {
        return str_replace($from, $to, $string);
    }

    public static function length(string $string): int {
        return strlen($string);
    }

    public static function random(int $length = 32): string {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $bytes = random_bytes($length - $len);

            $string .= substr(base64_encode($bytes), 0, $length - $len);
        }

        return $string;
    }

    public static function split(string $needle, string $string): array {
        return explode($needle, $string);
    }

    public static function splitAtOccurence(string $needle, int $occurence, string $string): array {
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
