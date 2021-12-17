<?php

namespace Melonly\Database;

use Melonly\Support\Containers\Vector;

abstract class Model {
    protected string $table;

    protected static function getTable(): string {
        $className = explode('\\', static::class);
        $className = strtolower(end($className)) . 's';

        $instance = new static();

        if (isset($instance->table)) {
            $className = $instance->table;
        }

        return $className;
    }

    public static function all(): Vector | Record {
        return DB::query('SELECT * FROM ' . self::getTable());
    }

    public static function create(array $data): void {
        $columns = [];
        $values = [];

        foreach ($data as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
        }

        DB::query(
            'INSERT INTO ' . self::getTable() . ' (id, ' . implode(',', $columns) . ') VALUES (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }
}
