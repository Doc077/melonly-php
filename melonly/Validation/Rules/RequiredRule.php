<?php

namespace Melonly\Validation\Rules;

class RequiredRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            return false;
        }
    }
}
