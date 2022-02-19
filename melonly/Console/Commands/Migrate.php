<?php

use Melonly\Console\Command;
use Melonly\Database\Facades\DB;
use Melonly\Filesystem\File;

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

        $previousMigratedList = glob(__DIR__ . '/../../../storage/migrations/*', GLOB_BRACE);
        $nothingToMigrate = true;

        foreach ($migrations as $migrationFile => $migration) {
            $tempFile = __DIR__ . '/../../../storage/migrations/' . $migrationFile;

            if (in_array($tempFile, $previousMigratedList)) {
                continue;
            }

            $nothingToMigrate = false;

            $schema = $migration->setup();

            $tableName = $schema->getTableName();
            $columns = $schema->getTableFields();

            $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (";

            /**
             * Build SQL query with columns and their types.
             */
            foreach ($columns as $column => $type) {
                $sql .= "`$column` $type," . PHP_EOL;
            }

            $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

            DB::query($sql);

            DB::query('
                ALTER TABLE `' . $tableName . '`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
            ');

            $previousMigratedList[] = $tempFile;

            File::create($tempFile);

            $this->infoLine("Migrated: $migrationFile");
        }

        if ($nothingToMigrate) {
            $this->infoLine('All migrations are already up to date');
        }
    }
};
