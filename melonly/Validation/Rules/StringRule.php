<?php

namespace Melonly\Validation\Rules;

class StringRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!is_string($value)) {
            return false;
        }
    }
}
