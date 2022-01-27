<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Database\DB;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        /**
         * Read all migration files.
         */
        $tables = [];
        $migrations = [];

        foreach (glob(__DIR__ . '/../../../database/migrations/*.melon', GLOB_BRACE) as $file) {
            $tableName = explode('/', $file);
            $tableName = explode('.', end($tableName));

            $tables[] = $tableName[0];
            $migrations[] = $file;
        }

        $iteration = 0;

        foreach ($migrations as $migration) {
            $sql = '
                CREATE TABLE IF NOT EXIST `' . $tables[$iteration] . '` (
            ';

            /**
             * Add columns.
             */
            $matchIterator = 0;

            foreach (File::read($migration) as $line) {
                if (preg_match('/^COLUMN (.*) TYPE (.*).$/', $line, $matches)) {
                    $type = $matches[2];

                    $type = Str::replace('text', 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL', $type);
                    $type = Str::replace('int', 'bigint(20) UNSIGNED NOT NULL', $type);
                    $type = Str::replace('id', 'bigint(20) UNSIGNED NOT NULL', $type);
                    $type = Str::replace('datetime', 'datetime DEFAULT CURRENT_TIMESTAMP', $type);
                    $type = Str::replace('timestamp', 'timestamp DEFAULT CURRENT_TIMESTAMP', $type);
                    
                    $sql .= '`' . $matches[1] . '` ' . $type . ($matchIterator === count(File::read($migration)) - 1 ? '' : ',') . PHP_EOL;
                }

                $matchIterator++;
            }

            /**
             * End SQL code and execute it.
             */
            $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

            DB::query($sql);

            DB::query('
                ALTER TABLE `' . $tables[$iteration] . '`
                ADD PRIMARY KEY (`id`);
            ');

            DB::query('
                ALTER TABLE `' . $tables[$iteration] . '`
                MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
            ');

            $this->infoLine("Created table $tables[$iteration]");

            $iteration++;
        }
    }
};
