<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../src/Controllers/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Controller '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../src/Controllers')) {
            mkdir(__DIR__ . '/../../../src/Controllers', 0777, true);
        }

        File::put($fileName, '<?php

namespace App\Controllers;

use Melonly\Routing\Attributes\Route;

class ' . $this->arguments[2] . ' {
    #[Route(path: \'/\')]
    public function index() {
        // 
    }
}
');

        $this->infoLine("Created controller '{$this->arguments[2]}'");
    }
};
