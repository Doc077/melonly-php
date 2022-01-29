<?php

namespace Melonly\Database;

use Melonly\Support\Containers\Vector;

class Query {
    protected string $sql = 'SELECT';
    protected string $table = '';

    protected array $wheres = [];

    public function setTable(string $tableName): self {
        $this->table = $tableName;

        return $this;
    }

    public function where(string $column, string $sign, string|int|float $value): self {
        if (is_string($value)) {
            $value = '"' . $value . '"';
        }

        $this->wheres[] = (count($this->wheres) > 0 ? ' AND ' : '') . $column . ' ' . $sign . $value;

        return $this;
    }

    public function orWhere(string $column, string $sign, string|int|float $value): self {
        if (is_string($value)) {
            $value = '"' . $value . '"';
        }

        $this->wheres[] = (count($this->wheres) > 0 ? ' OR ' : '') . $column . ' ' . $sign . $value;

        return $this;
    }

    public function fetch(array $columns = []): Vector|Record|array {
        $this->sql .= ' ' . (count($columns) > 0 ? implode(', ', $columns) : '*') . ' FROM ' . $this->table;
        $this->sql .= ' WHERE ' . implode('', $this->wheres);

        return DB::query($this->sql);
    }
}
