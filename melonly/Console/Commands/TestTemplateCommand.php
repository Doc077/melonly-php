<?php

namespace Melonly\Console\Commands;

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->publishFileFromTemplate(__DIR__ . '/../../../phpunit.xml', 'phpunit');

        $this->executeCommand('new:unit-test ExampleTest');
        $this->executeCommand('new:feature-test ExampleTest');

        $this->infoLine('Created testing template');
    }
};
