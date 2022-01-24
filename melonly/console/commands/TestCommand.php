<?php

namespace Melonly\Console;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Running tests');

        shell_exec('../../vendor/bin/phpunit tests');
    }
};
