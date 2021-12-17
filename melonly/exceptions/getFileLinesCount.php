<?php

if (!function_exists('getFileLinesCount')) {
    function getFileLinesCount(string $file): int {
        $file = new SplFileObject($file, 'r');

        $file->setFlags(SplFileObject::READ_AHEAD);
        $file->seek(PHP_INT_MAX);

        return $file->key() + 1;
    }
}
