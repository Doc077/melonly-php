<?php

namespace Melonly\Support\Helpers;

class Json
{
    public static function decode(string $data): mixed
    {
        return json_decode($data);
    }

    public static function encode(mixed $data): string|false
    {
        return json_encode($data);
    }
}
