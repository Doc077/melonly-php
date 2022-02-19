<?php

namespace Melonly\Container;

interface ContainerInterface
{
    public static function initialize(): void;

    public static function get(string $key): mixed;

    public static function set(string $class): mixed;

    public static function has(string $key): bool;

    public static function resolveDependencies(callable $callable): array;
}
