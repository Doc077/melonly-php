<?php

if (!function_exists('env')) {
    function env(string $data, mixed $default = null): mixed {
        if (!isset($_ENV[$data])) {
            if ($default === null) {
                throw new Exception("Config option '$data' is not set");
            } else {
                return $default;
            }
        }

        return $_ENV[$data];
    }
}
