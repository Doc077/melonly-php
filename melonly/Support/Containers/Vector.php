<?php

namespace Melonly\Support\Containers;

use ArrayAccess;
use Countable;
use Exception;
use InvalidArgumentException;
use Melonly\Support\Helpers\Json;

class Vector implements ArrayAccess, Countable
{
    protected array $items = [];

    protected string $type = 'integer';

    protected bool $firstTimeEmpty = true;

    public function __construct(...$values)
    {
        $array = [...$values];
        $this->items = $array;

        if (count($array) > 0) {
            $type = gettype($array[0]);

            foreach ($array as $item) {
                if ($type !== gettype($item)) {
                    throw new InvalidArgumentException("Vector must store items of the same type");
                }
            }

            $this->type = $type;

            $this->firstTimeEmpty = false;
        }
    }

    protected function add(mixed $value): void
    {
        if (count($this->items) === 0 && $this->firstTimeEmpty) {
            $this->type = gettype($value);

            $this->firstTimeEmpty = false;
        } elseif (gettype($value) !== $this->type) {
            throw new InvalidArgumentException("This vector can only store values of type {$this->type}");
        }

        $this->items[] = $value;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_string($offset)) {
            throw new InvalidArgumentException("Vector offset must be type of int");
        }

        $this->add($value);
    }

    public function offsetExists(mixed $offset): bool
    {
        if (!is_int($offset)) {
            return false;
        }

        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException("Vector offset must be type of int");
        }

        return $this->items[$offset];
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function append(...$values): void
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function average(): float
    {
        if ($this->type !== 'integer' || $this->type !== 'float' || $this->type !== 'double') {
            throw new Exception("Cannot get average value of non-numeric vector");
        }

        $items = array_filter($this->items);

        $avg = array_sum($items) / count($items);

        return $avg;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function every(callable $callback): bool
    {
        $every = true;

        for ($i = 0; $i < $this->length(); $i++) {
            if (!$callback($this->items[$i], $i)) {
                $every = false;

                break;
            }
        }

        return $every;
    }

    public function first(): mixed
    {
        return $this->items[0];
    }

    public function forEach(callable $callback): static
    {
        for ($i = 0; $i < $this->length(); $i++) {
            $callback($this->items[$i], $i);
        }

        return $this;
    }

    public function last(): mixed
    {
        return end($this->items);
    }

    public function length(): int
    {
        return count($this->items);
    }

    public function map(callable $callback): static
    {
        $items = $this->items;

        for ($i = 0; $i < count($items); $i++) {
            $items[$i] = $callback($items[$i], $i);
        }

        return new static(...$items);
    }

    public function merge(...$values): static
    {
        /**
         * Get the first element in case of array argument.
         */
        if (is_array($values[0])) {
            $new = new static(...$values[0]);

            foreach ($values[0] as $value) {
                $new->add($value);
            }
        } else {
            /**
             * In case of variadic arguments, merge them.
             */
            $new = new static(...$this->items);

            foreach ($values as $value) {
                $new->add($value);
            }
        }

        return $new;
    }

    public static function range(int $from, int $to): static
    {
        return new static(range($from, $to));
    }

    public function some(callable $callback): bool
    {
        $some = false;

        for ($i = 0; $i < $this->length(); $i++) {
            if ($callback($this->items[$i], $i)) {
                $some = true;

                break;
            }
        }

        return $some;
    }

    public function toJson(): string|false
    {
        return Json::encode($this->items);
    }

    public function unshift(...$values): static
    {
        foreach ($values as $value) {
            array_unshift($value, $this->items);
        }

        return $this;
    }
}
