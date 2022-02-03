<?php

namespace Melonly\Database;

use Melonly\Database\Facades\DB;
use Melonly\Support\Containers\Vector;

abstract class Model {
    protected string $table;

    public static array $fieldTypes = [];

    /**
     * Save record to database.
     */
    public function save(): void {
        $fields = [];
        $values = [];

        foreach (get_object_vars($this) as $field => $value) {
            $fields[] = $field;
            $values[] = $value;
        }

        DB::query(
            'insert into `' . self::getTable() . '` (id, ' . implode(',', $fields) . ') values (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }

    /**
     * Fetch all records from the table.
     */
    public static function all(): Vector|Record|array {
        return DB::query('SELECT * FROM ' . self::getTable());
    }

    public static function find(): self {
        return DB::query('SELECT * FROM ' . self::getTable());
    }

    /**
     * Create and save record.
     */
    public static function create(array $data): void {
        $fields = [];
        $values = [];

        foreach ($data as $field => $value) {
            self::validateFieldType($field, $value);

            $fields[] = $field;
            $values[] = $value;
        }

        DB::query(
            'insert into `' . self::getTable() . '` (id, ' . implode(',', $fields) . ') values (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }

    /**
     * Update and save record.
     */
    public static function update(array $data): void {
        $sets = '';

        foreach ($data as $field => $value) {
            self::validateFieldType($field, $value);

            $sets .= $field . ' = ' . $value;
        }

        DB::query('update `' . self::getTable() . '` set ' . $sets);
    }

    /**
     * Get model table name.
     */
    protected static function getTable(): string {
        $tableName = explode('\\', static::class);
        $tableName = strtolower(end($tableName)) . 's';

        $instance = new static();

        /**
         * Override default table name if provided.
         */
        if (isset($instance->table)) {
            $tableName = $instance->table;
        }

        return $tableName;
    }

    /**
     * Get model class name.
     */
    protected static function getClass(): string {
        $instance = new static();

        return get_class($instance);
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
            case 'find':
            case 'getTable':
            case 'update':
                return self::{$method}(...$args);

                break;

            default:
                return (new Query())->setTable(self::getTable())->{$method}(...$args);
        }
    }
}
