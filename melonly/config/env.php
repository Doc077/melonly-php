<?php

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        if (!isset($_ENV[$key])) {
            if ($default === null) {
                throw new Exception("Env option '$key' is not set");
            } else {
                return $default;
            }
        }

        if ($_ENV[$key] === false) {
            throw new Exception("Env option '$key' is invalid");
        }

        $value = getenv($key);

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}
