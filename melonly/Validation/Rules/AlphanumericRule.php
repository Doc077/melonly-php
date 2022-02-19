<?php

namespace Melonly\Validation\Rules;

class AlphanumericRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!ctype_alnum($_POST[$field])) {
            return false;
        }
    }
}
