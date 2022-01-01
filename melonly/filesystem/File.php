<?php

namespace Melonly\Filesystem;

use SplFileObject;
use ErrorException;

class File {
    public static function exists(string $path): bool {
        return file_exists($path);
    }

    public static function include(string $path): void {
        require_once $path;
    }

    public static function lines(string $path): int {
        $path = new SplFileObject($path, 'r');

        $path->setFlags(SplFileObject::READ_AHEAD);
        $path->seek(PHP_INT_MAX);

        return $path->key() + 1;
    }

    public static function hash(string $path): string | false {
        return md5_file($path);
    }

    public static function put(string $path, mixed $content, bool $lock = false): int | false {
        return file_put_contents($path, $content, $lock ? LOCK_EX : 0);
    }

    public static function append(string $path, mixed $content): int | false {
        return file_put_contents($path, $content, FILE_APPEND);
    }

    public static function delete(...$paths): bool {
        foreach ($paths as $path) {
            try {
                unlink($path);
            } catch (ErrorException) {
                return false;
            }
        }

        return true;
    }

    public static function copy(string $path, string $target): bool {
        return copy($path, $target);
    }

    public static function name(string $path): array | string {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function basename(string $path): array | string {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    public static function dirname(string $path): array | string {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    public static function extension(string $path): array | string {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function size(string $path): int | false {
        return filesize($path);
    }

    public static function symlink(string $target, string $link): bool {
        if (!strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return symlink($target, $link);
        }

        return false;
    }
}
