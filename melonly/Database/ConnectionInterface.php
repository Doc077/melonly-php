<?php

namespace Melonly\Database;

use PDO;

interface ConnectionInterface
{
    public function query(string $sql, string $modelClass = Record::class, array $boundParams = []): object|array;

    public function getConnection(): PDO;
}
