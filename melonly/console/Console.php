<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;
use Melonly\Database\DB;

class Console {
    protected array $arguments = [];

    public function __construct() {
        global $argv;

        foreach ($argv as $argument) {
            $this->arguments[] = $argument;
        }
    }

    public function version(): void {
        $table = new ConsoleTable();
    
        echo Color::LIGHT_BLUE, PHP_EOL;
    
        $table->addHeader('Version')
            ->addHeader('Released')
            ->addHeader('Author')
            ->addRow()
            ->addColumn(MELONLY_VERSION . ' STABLE RELEASE')
            ->addColumn('Jan 2022')
            ->addColumn('Dominik Rajkowski (dom.rajkowski@gmail.com)')
            ->display();
        
        echo Color::RESET, PHP_EOL;
    }

    public function server(): void {
        $port = 5000;

        if (isset($this->arguments[2]) && (int) $this->arguments[2] !== $port) {
            $port = $this->arguments[2];
        }

        echo Color::LIGHT_GREEN, "Starting Melonly development server [localhost:$port]", PHP_EOL, Color::RESET;

        shell_exec("php -S 127.0.0.1:$port public/index.php");
    }

    public function newComponent(): void {
        $fileName = __DIR__ . '/../../views/components/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Component \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../views/components')) {
            mkdir(__DIR__ . '/../../views/components', 0777, true);
        }

        file_put_contents($fileName, '<div class="' . strtolower($this->arguments[2]) . '">
    {{ $prop }}
</div>
');

        echo Color::LIGHT_GREEN, 'Created component \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function newController(): void {
        $fileName = __DIR__ . '/../../controllers/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Controller \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../controllers')) {
            mkdir(__DIR__ . '/../../controllers', 0777, true);
        }

        file_put_contents($fileName, '<?php

namespace App\Controllers;

use Melonly\Routing\Attributes\Route;

class ' . $this->arguments[2] . ' {
    #[Route(path: \'/\')]
    public function index() {
        
    }
}
');

        echo Color::LIGHT_GREEN, 'Created controller \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function newModel(): void {
        $fileName = __DIR__ . '/../../models/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Model \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../models')) {
            mkdir(__DIR__ . '/../../models', 0777, true);
        }

        file_put_contents($fileName, '<?php

namespace App\Models;

use Melonly\Database\Model;
use Melonly\Database\Attributes\Column;
use Melonly\Database\Attributes\IncrementingID;

class ' . $this->arguments[2] . ' extends Model {
    #[IncrementingID]
    public $id;

    #[Column(type: \'string\')]
    public $name;
}
');

        echo Color::LIGHT_GREEN, 'Created model \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function newPage(): void {
        $fileName = __DIR__ . '/../../views/pages/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Page \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../views/pages')) {
            mkdir(__DIR__ . '/../../views/pages', 0777, true);
        }

        file_put_contents($fileName, '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>' . ucfirst($this->arguments[2]) . '</title>
    </head>

    <body>
        <main></main>
    </body>
</html>
');

        echo Color::LIGHT_GREEN, 'Created page \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function newTable(): void {
        $fileName = __DIR__ . '/../../database/' . $this->arguments[2] . '.melon';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Table migration \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../database')) {
            mkdir(__DIR__ . '/../../database', 0777, true);
        }

        file_put_contents($fileName, 'COLUMN id TYPE id
COLUMN name TYPE text
COLUMN created_at TYPE datetime
COLUMN updated_at TYPE timestamp
');

        echo Color::LIGHT_GREEN, 'Created table migration \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function newView(): void {
        $fileName = __DIR__ . '/../../views/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'View \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../views')) {
            mkdir(__DIR__ . '/../../views', 0777, true);
        }

        file_put_contents($fileName, '<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>' . ucfirst($this->arguments[2]) . '</title>
    </head>

    <body>
        <main></main>
    </body>
</html>
');

        echo Color::LIGHT_GREEN, 'Created view \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function test(): void {
        echo Color::LIGHT_GREEN, 'Running tests', PHP_EOL, Color::RESET;

        shell_exec('../vendor/bin/phpunit tests');
    }

    public function migrate(): void {
        /**
         * Read all migration files.
         */
        $tables = [];
        $migrations = [];

        foreach (glob(__DIR__ . '/../../database/*.melon', GLOB_BRACE) as $file) {
            $tableName = explode('/', $file);
            $tableName = explode('.', end($tableName));

            $tables[] = $tableName[0];
            $migrations[] = $file;
        }

        $iteration = 0;

        foreach ($migrations as $migration) {
            $sql = '
                CREATE TABLE IF NOT EXISTS `' . $tables[$iteration] . '` (
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
}
