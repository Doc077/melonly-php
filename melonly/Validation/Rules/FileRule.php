<?php

namespace Melonly\Validation\Rules;

class FileRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        if (!isset($_FILES[$field]) || !is_file($_FILES[$field])) {
            return false;
        }
    }
}
