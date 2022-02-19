<?php

namespace Melonly\Validation;

interface ValidatorInterface
{
    public function check(array $array): bool;
}
