<?php

namespace Melonly\Validation;

use Melonly\Services\Facade;

class Validate extends Facade {
    protected static string $accessor = Validator::class;

    public static function __callStatic(string $method, array $args): mixed {
        parent::__callStatic($method, $args);
    }
}
