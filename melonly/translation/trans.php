<?php

use Melonly\Translation\Lang;

if (!function_exists('trans')) {
    function trans(string $key): string {
        $parts = explode('.', $key);

        $file = __DIR__ . '/../../frontend/lang/' . Lang::getCurrent() . '/' . $parts[0] . '.json';

        if (!file_exists($file)) {
            throw new Exception("Translation file '{$parts[0]}' does not exist");
        }

        $json = json_decode(file_get_contents($file), true);

        if (!array_key_exists($parts[1], $json)) {
            return $key;
        }

        return $json[$parts[1]];
    }
}
