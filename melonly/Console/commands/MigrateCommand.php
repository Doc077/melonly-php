<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Database\DB;
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

        $previousMigratedList = require_once __DIR__ . '/../../../storage/migrations/list.php';
        $nothingToMigrate = true;

        foreach ($migrations as $migrationFile => $migration) {
            if (in_array($migrationFile, $previousMigratedList)) {
                continue;
            }

            $nothingToMigrate = false;

            $schema = $migration->setup();

            $tableName = $schema->getTableName();
            $columns = $schema->getTableFields();

            $sql = "CREATE TABLE IF NOT EXIST `$tableName` (";

            /**
             * Build SQL query with columns and their types.
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

            $previousMigratedList[] = $migrationFile;

            $this->infoLine("Migrated: $migrationFile");
        }

        if ($nothingToMigrate) {
            $this->infoLine('All migrations are already up to date');
        }

        File::overwrite(__DIR__ . '/../../../storage/migrations/list.php', '<?php return ["' . implode('", "', $previousMigratedList) . '"];' . PHP_EOL);
    }
};
