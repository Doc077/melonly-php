<?php

namespace Melonly\Database;

use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\PrimaryKey;
use Melonly\Database\Facades\DB;
use Melonly\Support\Containers\Vector;
use ReflectionClass;
use ReflectionProperty;

abstract class Model
{
    protected string $table;

    public static array $fieldTypes = [];

    /**
     * Save record to database.
     */
    public function save(): void
    {
        $fields = [];
        $values = [];

        foreach (get_object_vars($this) as $field => $value) {
            if ($field !== 'id' && $field !== 'created_at') {
                self::validateFieldType($field, $value);

                $fields[] = $field;
                $values[] = $value;
            }
        }

        /**
         * Add id field if not provided.
         */
        if (!array_key_exists('id', $fields)) {
            array_unshift($fields, 'id');
        }

        DB::query(
            'insert into `' . self::getTable() . '` (' . implode(',', $fields) . ') values (NULL, \'' . implode('\',\'', $values) . '\')'
        );
    }

    /**
     * Fetch all records from the table.
     */
    public static function all(): Vector|Record|array
    {
        return DB::query('select * from ' . self::getTable());
    }

    /**
     * Find model by id.
     */
    public static function find(int|string $id): self
    {
        return DB::query('select * from ' . self::getTable() . ' where id = ' . $id);
    }

    /**
     * Create and save record.
     */
    public static function create(array $data): void
    {
        self::registerModel();

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
    public static function update(array $data): void
    {
        self::registerModel();

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
    protected static function getTable(): string
    {
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
    protected static function getClass(): string
    {
        $instance = new static();

        return get_class($instance);
    }

    protected static function validateFieldType(string $field, mixed $value): void
    {
        /**
         * Compare values with registered model data types.
         * 
         * Types are provided in model attributes.
         */
        if (array_key_exists($field, self::$fieldTypes) && self::$fieldTypes[$field] !== 'id' && self::$fieldTypes[$field] !== ['datetime']) {
            foreach (self::$fieldTypes[$field] as $type) {
                /**
                 * Allow nullable fields to be null.
                 */
                if ($type === 'null' || $type === null && $value === null) {
                    continue;
                }

                /**
                 * Validate other types.
                 */
                if ($type !== strtolower(gettype($value))) {
                    /**
                     * Create union type representation.
                     */
                    $union = implode('|', self::$fieldTypes[$field]);

                    throw new InvalidDataTypeException("Model field '$field' must be type of {$union}");
                }
            }
        }
    }

    /**
     * Register model column types by attributes.
     */
    protected static function registerModel(): void
    {
        if (count(self::$fieldTypes) > 0) {
            return;
        }

        $modelReflection = new ReflectionClass(self::getClass());

        /**
         * Get all model class properties.
         */
        $properties = $modelReflection->getProperties(ReflectionProperty::IS_PUBLIC);

        /**
         * Assign types to column names.
         */
        foreach ($properties as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if ($attribute->getName() === Column::class) {
                    /**
                     * Check whether field is nullable or not.
                     */
                    if (array_key_exists('nullable', $attribute->getArguments()) && $attribute->getArguments()['nullable'] === true) {
                        self::$fieldTypes[$property->getName()] = [
                            $attribute->getArguments()['type'],
                            'null',
                        ];
                    } else {
                        self::$fieldTypes[$property->getName()] = [
                            $attribute->getArguments()['type'],
                        ];
                    }
                } elseif ($attribute->getName() === PrimaryKey::class) {
                    self::$fieldTypes[$property->getName()] = 'id';
                }
            }
        }
    }

    /**
     * Handle all static calls for query builder interface.
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        return match ($method) {
            'all',
            'create',
            'find',
            'getTable',
            'update' => self::{$method}(...$args),

            default => (new Query())->setTable(self::getTable())->{$method}(...$args),
        };
    }
}
