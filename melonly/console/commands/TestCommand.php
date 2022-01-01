<?php

namespace Melonly\Console;

use Codedungeon\PHPCliColors\Color;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        echo Color::LIGHT_GREEN, 'Running tests', PHP_EOL, Color::RESET;

        shell_exec('../../vendor/bin/phpunit tests');
    }
};
