<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../../database/' . $this->arguments[2] . '.melon';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Table migration \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if doesn't exist.
         */
        if (!file_exists(__DIR__ . '/../../../database')) {
            mkdir(__DIR__ . '/../../../database', 0777, true);
        }

        file_put_contents($fileName, 'COLUMN id TYPE id
COLUMN name TYPE text
COLUMN created_at TYPE datetime
');

        echo Color::LIGHT_GREEN, "Created table migration '{$this->arguments[2]}'", PHP_EOL, Color::RESET;
    }
};
