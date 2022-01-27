<?php

namespace Melonly\Database;

class Schema {
    public static function createTable(string $name, callable $callback): self {
        return new self();
    }
}
