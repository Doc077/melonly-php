<?php

namespace Melonly\Support\Containers;

use ArrayAccess;
use Countable;
use Exception;

class Vector implements ArrayAccess, Countable {
    protected array $data = [];

    protected string $dataType = 'integer';

    protected bool $firstTimeEmpty = true;

    public function __construct(...$values) {
        $array = [...$values];
        $this->data = $array;

        if (count($array) > 0) {
            $type = gettype($array[0]);

            foreach ($array as $item) {
                if ($type !== gettype($item)) {
                    throw new Exception("Vector must store items of the same type");
                }
            }

            $this->dataType = $type;

            $this->firstTimeEmpty = false;
        }
    }

    public function count(): int {
        return count($this->data);
    }

    protected function add(mixed $value): void {
        if (count($this->data) === 0 && $this->firstTimeEmpty) {
            $this->dataType = gettype($value);

            $this->firstTimeEmpty = false;
        } elseif (gettype($value) !== $this->dataType) {
            throw new Exception("This vector can only store values of type {$this->dataType}");
        }

        $this->data[] = $value;
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        if (!is_int($offset)) {
            throw new Exception("Vector offset must be type of int");
        }

        $this->add($value);
    }

    public function offsetExists(mixed $offset): bool {
        if (!is_int($offset)) {
            return false;
        }

        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed {
        if (!is_int($offset)) {
            throw new Exception("Vector offset must be type of int");
        }

        return $this->data[$offset];
    }

    public function offsetUnset($offset): void {
        unset($this->data[$offset]);
    }

    public function append(...$values): void {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function length(): int {
        return count($this->data);
    }

    public function map(callable $callback): Vector {
        $array = array_map($callback, $this->data);

        $new = new self(...$array);

        return $new;
    }

    public function merge(...$values): Vector {
        /**
         * In case of array argument, get first element.
         */
        if (is_array($values[0])) {
            $new = new self(...$values[0]);

            foreach ($values[0] as $value) {
                $new->add($value);
            }
        } else {
            /**
             * In case of variadic arguments, merge them.
             */
            $new = new self(...$this->data);

            foreach ($values as $value) {
                $new->add($value);
            }
        }

        return $new;
    }
}
