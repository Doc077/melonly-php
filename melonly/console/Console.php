<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;

class Console {
    public static function version(): void {
        $table = new ConsoleTable();
    
        echo Color::LIGHT_BLUE, PHP_EOL;
    
        $table->addHeader('Version')
            ->addHeader('Release date')
            ->addHeader('Author')
            ->addRow()
            ->addColumn(MELONLY_VERSION . ' STABLE RELEASE')
            ->addColumn('Dec 2021')
            ->addColumn('Dominik Rajkowski (dom.rajkowski@gmail.com)')
            ->display();
        
        echo Color::RESET, PHP_EOL;
    }

    public static function server(): void {
        global $argv;

        $port = 5000;

        if (isset($argv[2]) && (int) $argv !== $port) {
            $port = $argv[2];
        }

        echo Color::LIGHT_GREEN, "Starting Melonly development server [localhost:$port]", PHP_EOL, Color::RESET;

        shell_exec("php -S 127.0.0.1:$port public/index.php");
    }

    public static function newComponent(): void {
        global $argv;

        $fileName = __DIR__ . '/../../views/components/' . $argv[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Component \'' . $argv[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../views/components')) {
            mkdir(__DIR__ . '/../../views/components', 0777, true);
        }

        file_put_contents($fileName, '<div>
    {{ $prop }}
</div>
');

        echo Color::LIGHT_GREEN, 'Created component \'' . $argv[2] . '\'', PHP_EOL, Color::RESET;
    }

    public static function newController(): void {
        global $argv;

        $fileName = __DIR__ . '/../../controllers/' . $argv[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Controller \'' . $argv[2] . '\' already exists', PHP_EOL, Color::RESET;

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

class '.$argv[2].' {
    #[Route(path: \'/\')]
    public function index() {
        
    }
}
');

        echo Color::LIGHT_GREEN, 'Created controller \'' . $argv[2] . '\'', PHP_EOL, Color::RESET;
    }

    public static function newModel(): void {
        global $argv;

        $fileName = __DIR__ . '/../../models/' . $argv[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Model \'' . $argv[2] . '\' already exists', PHP_EOL, Color::RESET;

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

class '.$argv[2].' extends Model {
    
}
');

        echo Color::LIGHT_GREEN, 'Created model \'' . $argv[2] . '\'', PHP_EOL, Color::RESET;
    }

    public static function newTable(): void {
        global $argv;

        $fileName = __DIR__ . '/../../database/' . $argv[2] . '.melon';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Table migration \'' . $argv[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if not exists.
         */
        if (!file_exists(__DIR__ . '/../../database')) {
            mkdir(__DIR__ . '/../../database', 0777, true);
        }

        file_put_contents($fileName, 'COLUMN id TYPE id
COLUMN column_name TYPE text
COLUMN created_at TYPE datetime
COLUMN updated_at TYPE timestamp
');

        echo Color::LIGHT_GREEN, 'Created table migration \'' . $argv[2] . '\'', PHP_EOL, Color::RESET;
    }

    public static function test(): void {
        echo Color::LIGHT_GREEN, 'Running tests', PHP_EOL, Color::RESET;

        shell_exec('../vendor/bin/phpunit tests');
    }
}
