<?php

namespace Melonly\Http;

use Exception;

class Cookie
{
    public static function get(string $key): mixed
    {
        if (!isset($_COOKIE[$key])) {
            throw new Exception("Cookie '{$key}' is not set");
        }

        return $_COOKIE[$key];
    }

    public static function set(string $key, mixed $value, mixed $expires): bool
    {
        return setcookie($key, $value, $expires);
    }
}
