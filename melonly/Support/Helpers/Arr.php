<?php

namespace Melonly\Support\Helpers;

class Arr
{
    public static function keyExists(array $array, string $key): bool
    {
        return array_key_exists($key, $array);
    }

    public static function push(array $array, ...$values): array
    {
        foreach ($values as $value) {
            $array[] = $value;
        }

        return $array;
    }

    public static function isAssociative(array $array): bool
    {
        if ($array === [])
            return false;

        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function first(array $array, callable $callback): mixed
    {
        foreach ($array as $element) {
            if ($callback($element)) {
                return $element;
            }
        }

        return false;
    }

    public static function head(array $array): mixed
    {
        return $array[0];
    }

    public static function last(array $array): mixed
    {
        return end($array);
    }
}
