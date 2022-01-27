<?php

namespace Melonly\Container;

interface ContainerInterface {
    public static function get(string $key): mixed;

    public static function has(string $key): bool;

    public static function initialize(): void;

    public static function resolve(callable $callable): array;
}
