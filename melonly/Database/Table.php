<?php

namespace Melonly\Database;

class Table {
    protected array $fields = [];

    public function id(string $name): void {
        $this->fields[$name] = 'bigint(20) UNSIGNED NOT NULL';
    }

    public function string(string $name): void {
        $this->fields[$name] = 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL';
    }

    public function int(string $name): void {
        $this->fields[$name] = 'bigint(20) UNSIGNED NOT NULL';
    }

    public function timestamp(string $name): void {
        $this->fields[$name] = 'timestamp DEFAULT CURRENT_TIMESTAMP';
    }

    public function getFields(): array {
        return $this->fields;
    }
}
