<?php

namespace Melonly\Http;

class Session
{
    public static function start(): void
    {
        if (php_sapi_name() !== 'cli') {
            session_start();
        }
    }

    public static function get(string $key): mixed
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function isSet(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        self::set("FLASH_$key", $value);
    }

    public static function getFlash(string $key): mixed
    {
        return self::get("FLASH_$key");
    }

    public static function hasFlash(string $key): bool
    {
        return self::isSet("FLASH_$key");
    }

    public static function unsetFlash(string $key): void
    {
        self::unset("FLASH_$key");
    }

    public static function clear(): void
    {
        session_unset();
        session_destroy();
    }
}
