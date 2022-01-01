<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $port = 5000;

        if (isset($this->arguments[2]) && (int) $this->arguments[2] !== $port) {
            $port = $this->arguments[2];
        }

        echo Color::LIGHT_GREEN, "Starting Melonly development server [localhost:$port]", PHP_EOL, Color::RESET;

        shell_exec("php -S 127.0.0.1:$port public/index.php");
    }
};
