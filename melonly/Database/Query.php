<?php

namespace Melonly\Database;

use Melonly\Database\Facades\DB;
use Melonly\Support\Containers\Vector;

class Query
{
    protected string $sql = 'select';

    protected string $table = '';

    protected array $columns = [];

    protected array $wheres = [];

    protected ?int $limit = null;

    protected ?string $orderBy = null;

    protected string $orderByMode = 'asc';

    public function where(string $column, string $sign, string|int|float $value): self
    {
        if (is_string($value)) {
            $value = '"' . $value . '"';
        }

        $this->wheres[] = (count($this->wheres) > 0 ? ' AND ' : '') . $column . ' ' . $sign . $value;

        return $this;
    }

    public function limit(int $offset): self
    {
        $this->limit = $offset;

        return $this;
    }

    public function orderBy(string $column, string $mode = 'asc'): self
    {
        $this->orderBy = $column;
        $this->mode = $mode;

        return $this;
    }

    public function orWhere(string $column, string $sign, string|int|float $value): self
    {
        if (is_string($value)) {
            $value = '"' . $value . '"';
        }

        $this->wheres[] = (count($this->wheres) > 0 ? ' or ' : '') . $column . ' ' . $sign . $value;

        return $this;
    }

    public function select(array $columns = []): self
    {
        $this->columns = array_merge($columns, $this->columns);

        return $this;
    }

    public function fetch(array $columns = []): Vector|Record|array
    {
        $this->columns = array_merge($columns, $this->columns);

        /**
         * Build final SQL query.
         */
        $this->sql .= ' ' . (count($this->columns) > 0 ? implode(', ', $this->columns) : '*') . ' from `' . $this->table . '`';

        if (count($this->wheres) > 0) {
            $this->sql .= ' where ' . implode('', $this->wheres);
        }

        if ($this->orderBy !== null) {
            $this->sql .= ' order by `' . $this->orderBy . '` ' . $this->orderByMode;
        }

        if ($this->limit !== null) {
            $this->sql .= ' limit ' . $this->limit;
        }

        return DB::query($this->sql);
    }

    public function setTable(string $tableName): self
    {
        $this->table = $tableName;

        return $this;
    }
}
