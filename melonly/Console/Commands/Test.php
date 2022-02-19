<?php

use Melonly\Console\Command;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        $this->infoLine('Running tests');

        exec('cd ' . __DIR__ . '/../../..');
        exec('"./vendor/bin/pest"');
    }
};
