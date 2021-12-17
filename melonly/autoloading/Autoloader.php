<?php

namespace Melonly\Utilities\Autoloading;

class Autoloader {
    public static function loadAll(string $path): void {
        foreach (glob($path . '/*.php', GLOB_BRACE) as $filename) {
            require_once $filename;
        }

        foreach (glob($path . '/attributes/*.php', GLOB_BRACE) as $filename) {
            require_once $filename;
        }
    }
}
