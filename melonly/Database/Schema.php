<?php

namespace Melonly\Database;

class Schema
{
    public function __construct(protected string $tableName, protected Table $table)
    {
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getTableFields(): array
    {
        return $this->table->getFields();
    }

    public static function table(string $name, callable $callback): self
    {
        $table = new Table();

        $callback($table);

        return new self($name, $table);
    }
}
