<?php

namespace Melonly\Logging;

class Log {
    public static function error(string $data, string $file): void {
        error_log($data, 3, __DIR__ . '/../../logs/' . $file . '.log');
    }

    public static function write(string $data, string $file): int | false {
        return file_put_contents($file, $data, FILE_APPEND);
    }
}
