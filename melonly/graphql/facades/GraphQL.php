<?php

namespace Melonly\GraphQL;

use Melonly\Services\Facade;

class GraphQL extends Facade {
    protected static function getAccessor(): string {
        return GraphQLServer::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
