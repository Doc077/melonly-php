<?php

namespace Melonly\Validation\Rules;

class AcceptedRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!$value && $value !== 'yes' && $value !== 'on') {
            return false;
        }
    }
}
