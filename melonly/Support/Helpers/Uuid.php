<?php

namespace Melonly\Support\Helpers;

use Ramsey\Uuid\Uuid as UuidGenerator;

class Uuid extends UuidGenerator
{
    public static function v1(): string
    {
        return UuidGenerator::uuid1()->toString();
    }

    public static function v2(int $localDomain): string
    {
        return UuidGenerator::uuid2($localDomain)->toString();
    }

    public static function v3(string $namespace, string $name): string
    {
        return UuidGenerator::uuid3($namespace, $name)->toString();
    }

    public static function v4(): string
    {
        return UuidGenerator::uuid4()->toString();
    }

    public static function v5(string $namespace, string $name): string
    {
        return UuidGenerator::uuid5($namespace, $name)->toString();
    }

    public static function v6(): string
    {
        return UuidGenerator::uuid6()->toString();
    }
}
