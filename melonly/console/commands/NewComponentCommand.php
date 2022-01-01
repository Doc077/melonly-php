<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $fileName = __DIR__ . '/../../views/components/' . $this->arguments[2] . '.php';

        if (file_exists($fileName)) {
            echo Color::LIGHT_RED, 'Component \'' . $this->arguments[2] . '\' already exists', PHP_EOL, Color::RESET;

            return;
        }

        /**
         * Create folder if doesn't exist.
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
};
