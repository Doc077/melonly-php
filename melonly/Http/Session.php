<?php

namespace Melonly\Http;

class Session {
    public static function start(): void {
        session_start();
    }

    public static function get(string $key): mixed {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function unset(string $key): void {
        unset($_SESSION[$key]);
    }

    public static function isSet(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public static function clear(): void {
        session_unset();
        session_destroy();
    }
}