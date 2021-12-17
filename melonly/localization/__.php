<?php

if (!function_exists('__')) {
    function __(string $key): string {
        return trans($key);
    }
}
