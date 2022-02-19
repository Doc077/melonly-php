<?php

namespace Melonly\Validation\Rules;

class NumberRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!is_numeric($_POST[$field])) {
            return false;
        }
    }
}
