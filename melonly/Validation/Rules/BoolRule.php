<?php

namespace Melonly\Validation\Rules;

class BoolRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!filter_var($_POST[$field], FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }
    }
}
