<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Database\DB;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $migrations = [];

        foreach (glob(__DIR__ . '/../../../database/migrations/*.php', GLOB_BRACE) as $file) {
            $class = require_once $file;

            $migrations[substr(preg_split('~/(?=[^/]*$)~', $file)[1], 0, -4)] = new $class();
        }

        foreach ($migrations as $migrationFile => $migration) {
            $schema = $migration->setup();

            $tableName = $schema->getTableName();
            $columns = $schema->getTableFields();

            $sql = "CREATE TABLE IF NOT EXIST `$tableName` (";

            /**
             * Build query with columns and their types.
             */
            foreach ($columns as $column => $type) {
                $sql .= "$column $type,";
            }

            $sql .= 'PRIMARY KEY (id)) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

            DB::query($sql);

            DB::query('
                ALTER TABLE `' . $tableName . '`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
            ');

            $this->infoLine("Migrated: $migrationFile");
        }
    }
};
