<?php

namespace Melonly\Console;

use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../controllers/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Controller '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../controllers')) {
            mkdir(__DIR__ . '/../../../controllers', 0777, true);
        }

        File::put($fileName, '<?php

namespace App\Controllers;

use Melonly\Routing\Attributes\Route;

class ' . $this->arguments[2] . ' {
    #[Route(path: \'/\')]
    public function index() {
        
    }
}
');

        $this->infoLine("Created controller '{$this->arguments[2]}'");
    }
};
