<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use Melonly\Database\DB;

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

        foreach (glob(__DIR__ . '/../../../database/*.melon', GLOB_BRACE) as $file) {
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

            foreach (file($migration) as $line) {
                if (preg_match('/^COLUMN (.*) TYPE (.*).$/', $line, $matches)) {
                    $type = $matches[2];

                    $type = str_replace('text', 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL', $type);
                    $type = str_replace('int', 'bigint(20) UNSIGNED NOT NULL', $type);
                    $type = str_replace('id', 'bigint(20) UNSIGNED NOT NULL', $type);
                    $type = str_replace('datetime', 'datetime DEFAULT CURRENT_TIMESTAMP', $type);
                    $type = str_replace('timestamp', 'timestamp DEFAULT CURRENT_TIMESTAMP', $type);
                    
                    $sql .= '`' . $matches[1] . '` ' . $type . ($matchIterator === count(file($migration)) - 1 ? '' : ',') . PHP_EOL;
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

            echo Color::LIGHT_GREEN, "Created table $tables[$iteration]", PHP_EOL, Color::RESET;

            $iteration++;
        }
    }
};
