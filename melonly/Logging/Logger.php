<?php

namespace Melonly\Logging;

class Logger
{
    public function error(string $data, string $file): void
    {
        error_log($data, 3, __DIR__ . '/../../storage/logs/' . $file . '.log');
    }

    public function info(string $data, string $file): int|false
    {
        return file_put_contents($file, $data, FILE_APPEND);
    }
}
