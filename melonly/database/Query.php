<?php

namespace Melonly\Database;

use Melonly\Support\Containers\Vector;

class Query {
    protected string $sql = 'SELECT';
    protected array $wheres = [];

    public function where($column, $sign, $value): self {
        $this->wheres[$column . ' ' . $sign] = $value;

        return $this;
    }

    public function fetch(): Vector | Record | array {
        $this->sql .= ' WHERE ' . implode(' AND ', $this->wheres);

        return DB::query($this->sql);
    }
}
