<?php

if (!function_exists('throwIf')) {
    function throwIf(bool $condition, string | object $exception, ...$params): never {
        if ($condition) {
            throw (is_string($exception) ? new $exception($params) : $exception($params));
        }
    }
}
