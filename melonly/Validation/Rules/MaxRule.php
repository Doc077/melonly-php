<?php

namespace Melonly\Validation\Rules;

class MaxRule
{
    public static function check(mixed $value, ?string $ruleValue): bool
    {
        if (is_int($value)) {
            if ($value <= (int) $ruleValue) {
                return true;
            }

            return false;
        } elseif (is_string($value)) {
            if (strlen($value) <= $ruleValue) {
                return true;
            }

            return false;
        }
    }
}
