<?php

namespace Melonly\Validation\Rules;

class RegexRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        return preg_match($ruleValue, $value);
    }
}
