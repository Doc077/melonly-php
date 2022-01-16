<?php

namespace Melonly\Http;

class Session {
    public static function get(string $key): mixed {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    public static function set(string $key, mixed $value): mixed {
        $_SESSION[$key] = $value;
    }

    public static function isSet(string $key): bool {
        return isset($_SESSION[$key]);
    }
}
