<?php

namespace Melonly\Autoloading;

class Autoloader {
    protected const PATTERNS = [
        '/*.php',
    ];

    public static function loadFiles(string $path): void {
        foreach (self::PATTERNS as $pattern) {
            foreach (glob($path . $pattern, GLOB_BRACE) as $filename) {
                require_once $filename;
            }
        }
    }
}
