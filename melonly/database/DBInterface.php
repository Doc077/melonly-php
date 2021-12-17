<?php

namespace Melonly\Database;

interface DBInterface {
    public static function query(string $sql, array $boundParams = []): mixed;
}
