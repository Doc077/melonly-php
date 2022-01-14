<?php

namespace Melonly\Mailing;

use Melonly\Services\Facade;

class Mail extends Facade {
    protected static function getAccessor(): string {
        return Mailer::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        parent::__callStatic($method, $args);
    }
}
