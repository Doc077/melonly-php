<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;
use LucidFrame\Console\ConsoleTable;

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
            ->addHeader('Release date')
            ->addHeader('Author')
            ->addRow()
            ->addColumn(MELONLY_VERSION . ' STABLE RELEASE')
            ->addColumn('Dec 2021')
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

class '.$this->arguments[2].' {
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

class '.$this->arguments[2].' extends Model {
    
}
');

        echo Color::LIGHT_GREEN, 'Created model \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
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
COLUMN column_name TYPE text
COLUMN created_at TYPE datetime
COLUMN updated_at TYPE timestamp
');

        echo Color::LIGHT_GREEN, 'Created table migration \'' . $this->arguments[2] . '\'', PHP_EOL, Color::RESET;
    }

    public function test(): void {
        echo Color::LIGHT_GREEN, 'Running tests', PHP_EOL, Color::RESET;

        shell_exec('../vendor/bin/phpunit tests');
    }

    public function database(): void {

    }
}
