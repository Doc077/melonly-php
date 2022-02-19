<?php

namespace Melonly\Validation\Rules;

use Melonly\Database\Facades\DB;

class UniqueRule
{
    public static function check(string $field, mixed $value, ?string $ruleValue): bool
    {
        $result = DB::query("select count(distinct `$field`) as `unique`, count(`$field`) as `total` from `$ruleValue`");

        return $result->unique === $result->total;
    }
}
