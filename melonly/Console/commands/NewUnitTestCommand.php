<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../tests/Unit/' . $this->arguments[2] . '.php';

        if (File::exists($fileName)) {
            $this->errorLine("Unit test '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists($folder = __DIR__ . '/../../../tests/Unit')) {
            File::makeDirectory($folder);
        }

        $this->publishFileFromTemplate($fileName, 'unit-test', [
            'class' => $this->arguments[2],
        ]);

        $this->infoLine("Created unit test '{$this->arguments[2]}'");
    }
};
