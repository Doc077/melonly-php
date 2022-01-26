<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../database/' . $this->arguments[2] . '.melon';

        if (File::exists($fileName)) {
            $this->errorLine("Table migration '{$this->arguments[2]}' already exists");

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!File::exists(__DIR__ . '/../../../database')) {
            mkdir(__DIR__ . '/../../../database', 0777, true);
        }

        File::put($fileName, 'COLUMN id TYPE id
COLUMN name TYPE text
COLUMN created_at TYPE datetime
');

        $this->infoLine("Created table migration '{$this->arguments[2]}'");
    }
};
