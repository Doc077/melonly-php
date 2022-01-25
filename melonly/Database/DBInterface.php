<?php

namespace Melonly\Database;

interface DBInterface {
    public static function query(string $sql, string $modelClass, array $boundParams = []): mixed;
}
