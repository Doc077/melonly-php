<?php

namespace Melonly\Filesystem;

use ErrorException;
use SplFileObject;

class File
{
    protected readonly string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function exists(string $path): bool
    {
        return file_exists($path);
    }

    public static function content(string $path): string|false
    {
        return file_get_contents($path);
    }

    public static function create(string $path, string $content = ''): int|false
    {
        if (!self::exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        return file_put_contents($path, $content);
    }

    public static function include(string $path): void
    {
        require_once $path;
    }

    public static function lines(string $path): int
    {
        $lines = 0;

        $path = new SplFileObject($path, 'r');

        $path->setFlags(SplFileObject::READ_AHEAD);
        $path->seek(PHP_INT_MAX);

        $lines = $path->key() + 1;

        return $lines;
    }

    public static function hash(string $path): string|false
    {
        return md5_file($path);
    }

    public static function put(string $path, mixed $content, bool $lock = false): int|false
    {
        return file_put_contents($path, $content, $lock ? LOCK_EX : 0);
    }

    public static function append(string $path, mixed $content): int|false
    {
        return file_put_contents($path, $content, FILE_APPEND);
    }

    public static function overwrite(string $path, mixed $content): int|false
    {
        return file_put_contents($path, $content);
    }

    public static function delete(...$paths): bool
    {
        foreach ($paths as $path) {
            try {
                unlink($path);
            } catch (ErrorException) {
                return false;
            }
        }

        return true;
    }

    public static function copy(string $path, string $target): bool
    {
        return copy($path, $target);
    }

    public static function name(string $path): array|string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function makeDirectory(string $path): void
    {
        mkdir($path, 0777, true);
    }

    public static function basename(string $path): array|string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    public static function dirname(string $path): array|string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    public static function extension(string $path): array|string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function read(string $path, int $flags = 0): array|false
    {
        return file($path, $flags);
    }

    public static function size(string $path): int|false
    {
        return filesize($path);
    }

    public static function symlink(string $target, string $link): bool
    {
        if (!strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return symlink($target, $link);
        }

        return false;
    }
}
