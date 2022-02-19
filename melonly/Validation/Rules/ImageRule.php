<?php

namespace Melonly\Validation\Rules;

class ImageRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        $allowedTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];

        if (!in_array($_FILES[$field]['type'], $allowedTypes)) {
            return false;
        }
    }
}
