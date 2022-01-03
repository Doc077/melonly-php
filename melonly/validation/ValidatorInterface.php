<?php

namespace Melonly\Validation;

interface ValidatorInterface {
    public static function check(array $array): bool;
}
