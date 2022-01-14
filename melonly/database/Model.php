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
            self::validateFieldType($field, $value);

            $fields[] = $field;
            $values[] = $value;
        }

        DB::query(
            'INSERT INTO ' . self::getTable() . ' (id, ' . implode(',', $fields) . ') VALUES (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }

    public static function update(array $data): void {
        $sets = '';

        foreach ($data as $field => $value) {
            self::validateFieldType($field, $value);

            $sets .= $field . ' = ' . $value;
        }

        DB::query('UPDATE ' . self::getTable() . ' SET ' . $sets);
    }

    protected static function validateFieldType(string $field, mixed $value): void {
        /**
         * Compare values with registered model data types.
         * Types are supplied by model attributes.
         */
        if (self::$fieldTypes[$field] !== 'id' && self::$fieldTypes[$field] !== ['datetime']) {
            foreach (self::$fieldTypes[$field] as $type) {
                if ($type !== strtolower(gettype($value))) {
                    /**
                     * Create union type representation.
                     */
                    $union = implode('|', self::$fieldTypes);

                    throw new InvalidDataTypeException("Invalid model data type: field $field must be type of {$union}");
                }
            }
        }
    }

    /**
     * Handle all static calls for query builder interface.
     */
    public static function __callStatic(string $method, array $args): mixed {
        switch ($method) {
            case 'all':
            case 'create':
            case 'getTable':
            case 'update':
                return self::{$method}(...$args);

                break;

            default:
                return (new Query())->setTable(self::getTable())->{$method}(...$args);
        }
    }
}
