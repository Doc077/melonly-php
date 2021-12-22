<?php

namespace Melonly\Support\Containers;

use ArrayAccess;
use Countable;
use Exception;

class Vector implements ArrayAccess, Countable {
    protected array $items = [];

    protected string $type = 'integer';

    protected bool $firstTimeEmpty = true;

    public function __construct(...$values) {
        $array = [...$values];
        $this->items = $array;

        if (count($array) > 0) {
            $type = gettype($array[0]);

            foreach ($array as $item) {
                if ($type !== gettype($item)) {
                    throw new Exception("Vector must store items of the same type");
                }
            }

            $this->type = $type;

            $this->firstTimeEmpty = false;
        }
    }

    protected function add(mixed $value): void {
        if (count($this->items) === 0 && $this->firstTimeEmpty) {
            $this->type = gettype($value);

            $this->firstTimeEmpty = false;
        } elseif (gettype($value) !== $this->type) {
            throw new Exception("This vector can only store values of type {$this->type}");
        }

        $this->items[] = $value;
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

        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed {
        if (!is_int($offset)) {
            throw new Exception("Vector offset must be type of int");
        }

        return $this->items[$offset];
    }

    public function offsetUnset($offset): void {
        unset($this->items[$offset]);
    }

    public function all(): array {
        return $this->items;
    }

    public function average(): float {
        if ($this->type !== 'integer' || $this->type !== 'float' || $this->type !== 'double') {
            throw new Exception("Cannot get average value of non-numeric vector");
        }

        $items = array_filter($this->items);

        $avg = array_sum($items) / count($items);

        return $avg;
    }

    public function count(): int {
        return count($this->items);
    }

    public function append(...$values): void {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function length(): int {
        return count($this->items);
    }

    public function map(callable $callback): Vector {
        $array = array_map($callback, $this->items);

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
            $new = new self(...$this->items);

            foreach ($values as $value) {
                $new->add($value);
            }
        }

        return $new;
    }

    public static function range(int $from, int $to): static {
        return new static(range($from, $to));
    }
}
