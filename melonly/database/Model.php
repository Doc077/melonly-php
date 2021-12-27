<?php

namespace Melonly\Database;

use Melonly\Support\Containers\Vector;

abstract class Model {
    protected string $table;

    public static array $columnTypes = [];

    protected static function getTable(): string {
        $tableName = explode('\\', static::class);
        $tableName = strtolower(end($tableName)) . 's';

        $instance = new static();

        if (isset($instance->table)) {
            $tableName = $instance->table;
        }

        return $tableName;
    }

    public static function all(): Vector | Record | array {
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

    public static function __callStatic(string $method, array $args): mixed {
        switch ($method) {
            case 'all':
            case 'create':
            case 'getTable':
                return self::{$method}(...$args);

                break;

            default:
                return (new Query())->setTable(self::getTable())->{$method}(...$args);
        }
    }
}
