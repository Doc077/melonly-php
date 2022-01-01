<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../controllers/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Controller \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if doesn't exist.
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
};
