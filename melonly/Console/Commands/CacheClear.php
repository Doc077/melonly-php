<?php

use Melonly\Console\Command;
use Melonly\Filesystem\File;

return new class extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function handle(): void {
        foreach (glob(__DIR__ . '/../../../storage/cache/*.php', GLOB_BRACE) as $file) {
            File::delete($file);
        }

        $this->infoLine('Cleared cache');
    }
};
