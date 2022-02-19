<?php

namespace Melonly\Database;

class Table
{
    protected array $fields = [];

    public function id(): void
    {
        $this->fields['id'] = 'bigint(20) UNSIGNED NOT NULL';
    }

    public function string(string $name, bool $nullable = false): void
    {
        $this->fields[$name] = 'varchar(255) COLLATE utf8mb4_unicode_ci' . ($nullable ? '' : ' NOT NULL');
    }

    public function int(string $name, bool $nullable = false): void
    {
        $this->fields[$name] = 'bigint(20)' . ($nullable ? '' : ' NOT NULL');
    }

    public function float(string $name, bool $nullable = false): void
    {
        $this->fields[$name] = 'float(28)' . ($nullable ? '' : ' NOT NULL');
    }

    public function bool(string $name, bool $nullable = false): void
    {
        $this->fields[$name] = 'bool' . ($nullable ? '' : ' NOT NULL');
    }

    public function enum(string $name, array $values, bool $nullable = false): void
    {
        $this->fields[$name] = 'enum(' . implode(',', $values) . ')' . ($nullable ? '' : ' NOT NULL');
    }

    public function timestamp(string $name, bool $nullable = true): void
    {
        $this->fields[$name] = 'timestamp DEFAULT CURRENT_TIMESTAMP' . ($nullable ? '' : ' NOT NULL');
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
