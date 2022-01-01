<?php

namespace Melonly\Database;

use Exception;
use Melonly\Support\Containers\Vector;

abstract class Model {
    protected string $table;

    public static array $fieldTypes = [];

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
        $fields = [];
        $values = [];

        foreach ($data as $field => $value) {
            /**
             * Compare values with registered model data types.
             * Types are supplied by model attributes.
             */
            if (self::$fieldTypes[$field] !== 'id') {
                foreach (self::$fieldTypes[$field] as $type) {
                    if (self::$fieldTypes[$field] !== gettype($value)) {
                        throw new Exception("Invalid model data type: field $field must be type of {$type}");
                    }
                }
            }

            $fields[] = $field;
            $values[] = $value;
        }

        DB::query(
            'INSERT INTO ' . self::getTable() . ' (id, ' . implode(',', $fields) . ') VALUES (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }

    /**
     * Handle all static calls for query builder interface.
     */
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
